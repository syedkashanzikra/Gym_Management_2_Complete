<?php

namespace App\Http\Controllers\Admin\Shop;

use App\Models\Shop\Order;
use App\Services\Resource;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Coderstm\Events\RefundProcessed;
use Illuminate\Http\Resources\Json\ResourceCollection;

class OrderController extends Controller
{
    public function index(Request $request, Order $order)
    {
        $orders = $order->query();

        if ($request->filled('filter')) {
            $orders->whereRaw('(SELECT CONCAT(`first_name`, `first_name`) AS name FROM users WHERE users.id = orders.customer_id) like ?', ["%{$request->filter}%"]);
        }

        if ($request->filled('customer')) {
            $orders->whereHas('customer', function ($query) use ($request) {
                $query->where('id', $request->customer);
            });
        }

        if ($request->filled('status')) {
            $orders->whereHasStatus($request->status);
        }

        if ($request->boolean('deleted')) {
            $orders->onlyTrashed();
        }

        $orders = $orders->sortBy($request->sortBy, $request->descending)
            ->paginate($request->rowsPerPage ?? 15);
        return new ResourceCollection($orders);
    }

    public function store(Request $request, Order $order)
    {
        $rules = [
            'line_items' => 'required',
            'line_items.*.title' => 'required|string',
            'line_items.*.discount.value' => 'required_if:line_items.*.discount,not_null',
            'line_items.*.discount.type' => 'required_if:line_items.*.discount,not_null',
            'payment_method' => 'sometimes|exists:payment_methods,id',
        ];

        $this->validate($request, $rules);

        $order = $order->createOrUpdate(new Resource($request->input()));

        if ($request->filled('payment_method')) {
            $order->markAsPaid($request->payment_method);
        }

        return response()->json([
            'data' => $order->fresh([
                'billing_address',
                'discount',
                'location',
                'logs.admin',
            ]),
            'message' => 'Order has been created successfully!',
        ], 200);
    }

    public function show(Order $order)
    {
        return response()->json($order->load([
            'billing_address',
            'location',
            'payments',
            'logs.admin',
        ]), 200);
    }

    public function update(Request $request, Order $order)
    {
        // Set rules
        $rules = [
            'line_items' => 'array|required',
            'line_items.*.title' => 'required|string',
            'line_items.*.discount.value' => 'required_if:line_items.*.discount,not_null',
            'line_items.*.discount.type' => 'required_if:line_items.*.discount,not_null',
            'payment_method' => 'sometimes|exists:payment_methods,id',
        ];

        // Validate those rules
        $this->validate($request, $rules);

        if (!$order->can_edit) {
            return response()->json([
                'message' => 'Canceled/Completed orders can’t be edited.',
            ], 200);
        }

        $order = $order->createOrUpdate(new Resource($request->input()));

        if ($request->filled('payment_method')) {
            $order->markAsPaid($request->payment_method);
        } else if ($order->is_paid && $order->has_due) {
            $order->markedAsPartiallyPaid();
        }

        return response()->json([
            'data' => $order->fresh([
                'billing_address',
                'discount',
                'location',
                'payments',
                'logs.admin',
            ]),
            'message' => 'Order has been updated successfully!',
        ], 200);
    }

    public function destroy(Order $order)
    {
        $order->delete();
        return response()->json([
            'message' => 'Order has been deleted successfully!',
        ], 200);
    }

    public function destroySelected(Request $request, Order $order)
    {
        $this->validate($request, [
            'items' => 'required',
        ]);
        $order->whereIn('id', $request->items)->each(function ($item) {
            $item->delete();
        });
        return response()->json([
            'message' => trans_modules('destroy', 'order'),
        ], 200);
    }

    public function restore($id)
    {
        Order::onlyTrashed()
            ->where('id', $id)->each(function ($item) {
                $item->restore();
            });
        return response()->json([
            'message' => 'Order has been restored successfully!',
        ], 200);
    }

    public function restoreSelected(Request $request, Order $order)
    {
        $this->validate($request, [
            'items' => 'required',
        ]);
        $order->onlyTrashed()
            ->whereIn('id', $request->items)->each(function ($item) {
                $item->restore();
            });
        return response()->json([
            'message' => 'Orders has been restored successfully!',
        ], 200);
    }

    public function logs(Request $request, Order $order)
    {
        // Set rules
        $rules = [
            'message' => 'required',
        ];

        // Validate those rules
        $this->validate($request, $rules);

        $comment = $order->logs()->create($request->input());

        // Update media
        if ($request->has('media')) {
            $comment->setMedia($request->input('media'));
        }

        return response()->json([
            'data' => $comment->load('admin', 'media'),
            'message' => 'Comment has been added successfully!',
        ], 200);
    }

    public function markAsPaid(Request $request, Order $order)
    {
        // Set rules
        $rules = [
            'payment_method' => 'required|exists:payment_methods,id',
        ];

        // Validate those rules
        $this->validate($request, $rules);

        $order->markAsPaid($request->payment_method);

        return response()->json([
            'message' => 'Order manually marked as paid successfully!',
        ], 200);
    }

    public function duplicate(Order $order)
    {

        $order = $order->duplicate();

        return response()->json([
            'data' => $order->fresh([
                'billing_address',
                'discount',
                'logs.admin',
            ]),
            'message' => 'Order has been duplicated successfully!',
        ], 200);
    }

    public function cancel(Request $request, Order $order)
    {
        if ($order->is_cancelled) {
            abort(403, 'Order already canceled.');
        }

        $order->markedAsCancelled();

        if ($request->boolean('refund')) {
            $refund = $order->refunds()->create([
                'amount' => $order->fresh()->refundable_amount,
            ]);
            $order->markedAsRefunded();
            event(new RefundProcessed($refund));
        }

        if ($request->boolean('restock')) {
            $order->restock();
        }

        $order->logs()->create([
            'type' => "canceled",
            'message' => "Order has been canceled. Reason: " . constant("App\Models\Shop\Order::REASON_{$request->reason}"),
        ]);

        return response()->json([
            'data' => $order->fresh([
                'billing_address',
                'discount',
                'status',
                'logs.admin',
            ]),
            'message' => 'Order has been canceled successfully!',
        ], 200);
    }

    public function receipt(Order $order)
    {
        return $order->receiptPdf()->download("receipt-{$order->id}.pdf");
    }

    public function pos(Order $order)
    {
        return $order->posPdf()->download("receipt-{$order->id}.pdf");
    }
}
