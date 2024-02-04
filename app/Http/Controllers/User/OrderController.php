<?php

namespace App\Http\Controllers\User;

use App\Models\Enquiry;
use App\Models\Shop\Order;
use App\Services\Resource;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Shop\Product\Variant;
use Illuminate\Http\Resources\Json\ResourceCollection;

class OrderController extends Controller
{
    public function index(Request $request, Order $order)
    {
        $order = $order->onlyOwner();

        $order = $order->orderBy(optional($request)->sortBy ?? 'created_at', optional($request)->direction ?? 'desc')
            ->paginate(optional($request)->rowsPerPage ?? 15);
        return new ResourceCollection($order);
    }

    public function store(Request $request, Order $order)
    {
        $rules = [
            'line_items' => 'required|array',
        ];
        $customer = current_user()->toArray();
        $request->merge([
            'status' => 'Open',
            'source' => 'Enquiry',
            'customer' => $customer,
            'contact' => [
                'email' => $customer['email'],
                'phone_number' => $customer['phone_number'],
            ],
            'billing_address' => array_merge($customer['address'], [
                'first_name' => $customer['first_name'],
                'last_name' => $customer['last_name'],
                'phone_number' => $customer['phone_number'],
            ]),
            'line_items' => collect($request->line_items)->map(function ($item) {
                return array_merge($item, [
                    'variant_title' => optional(Variant::find($item['variant_id']))->title
                ]);
            })->toArray(),
        ]);

        // Validate those rules
        $this->validate($request, $rules);

        // create the order
        $order = $order->createOrUpdate(new Resource($request->input()));

        $enquiry = Enquiry::create([
            'subject' => "Order #{$order->id} Enquiry",
            'message' => $request->note,
            'order_id' => $order->id,
        ]);

        return response()->json([
            'data' => $order->fresh(['line_items']),
            'enquiry' => $enquiry,
            'message' => trans_module('store', 'order'),
        ], 200);
    }

    public function show(Order $order)
    {
        return response()->json($order, 200);
    }
}
