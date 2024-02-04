<?php

namespace App\Http\Controllers;

use App\Models\Shop\Order;
use Coderstm\Models\Address;
use Illuminate\Http\Request;
use App\Models\Shop\Customer;
use App\Models\Shop\Order\Contact;
use Coderstm\Models\PaymentMethod;
use App\Models\Shop\CartRepository;
use App\Models\Shop\Order\LineItem;
use App\Models\Shop\Order\DiscountLine;

class ShopController extends Controller
{
    public function pay(Request $request, Order $order, PaymentMethod $paymentMethod)
    {
        $rules = [
            'payment' => 'required',
        ];

        $message = [
            'payment.required' => 'Payment is required to process the order.',
        ];

        $this->validate($request, $rules, $message);

        return $this->paymentProcess($request, $order, $paymentMethod);
    }

    public function checkout(Request $request, PaymentMethod $paymentMethod, Order $order)
    {
        $rules = [
            'payment' => 'required',
            'line_items' => 'array',
            'line_items.*.title' => 'required|string',
            'line_items.*.discount.value' => 'required_if:line_items.*.discount,not_null',
            'line_items.*.discount.type' => 'required_if:line_items.*.discount,not_null',
        ];

        $this->validate($request, $rules, [
            'payment.required' => 'Payment is required to process the order.',
            'line_items.*.title.required' => 'Line items title is required to process payment.',
        ]);

        $customer = Customer::find(current_user()->id);

        $request->merge([
            'customer' => $customer->toArray(),
        ]);

        // create order
        $order = $order->create($request->input());

        // save order's realted model
        $order->saveRelated($request);

        return $this->paymentProcess($request, $order, $paymentMethod);
    }

    public function paymentProcess(Request $request, Order $order, PaymentMethod $paymentMethod)
    {
        try {
            $transaction = $order->customer->charge($order->grand_total * 100, $request->input('payment.id'));

            if ($transaction->isSucceeded()) {
                // Create payment against order
                $order->payments()->create([
                    'payment_method_id' => $paymentMethod->id,
                    'transaction_id' => $transaction->id,
                    'amount' => $transaction->rawAmount / 100,
                    'status' => $transaction->status,
                ]);
            }

            return response()->json($transaction->asStripePaymentIntent(), 200);
        } catch (\Exception $e) {
            throw $e;
        }
    }

    public function calculator(Request $request)
    {
        $request->merge([
            'line_items' => $request->line_items ?? [],
        ]);

        $order = Order::firstOrNew(['id' => $request->id], []);

        $line_items = collect($request->line_items)->map(function ($product) {
            return LineItem::firstOrNew([
                'id' => has($product)->id,
            ], $product)->fill($product);
        });

        $order->created_at = $order->created_at ?? now();

        $order->fill($request->only([
            'note',
            'collect_tax',
            'attributes',
            'source',
        ]));

        $order->setRelation('line_items', $line_items);

        if ($request->filled('line_items')) {
            foreach ($request->line_items as $key => $product) {
                if (isset($product['discount'])) {
                    $order->line_items[$key]->setRelation('discount', DiscountLine::firstOrNew([
                        'id' => has($product['discount'])->id,
                    ], $product['discount'])->fill($product['discount']));
                }
            }
        }

        $order->line_items_quantity = $order->line_items->sum('quantity');

        $order->setRelation('discount', $request->filled('discount') ? new DiscountLine($request->discount) : null);

        if ($request->filled('customer')) {
            $customer = new Customer($request->customer);
            if ($request->filled('customer.address')) {
                $customer->setRelation('address', new Address($request->input('customer.address')));
            }
            $order->setRelation('customer', $customer);
            $order->customer->created_at = $order->customer->created_at ?? now();
            if ($request->filled('customer.id')) {
                $order->customer->id = $request->input('customer.id');
            }
        } else {
            $order->setRelation('customer', null);
        }

        $order->setRelation('contact', $request->filled('contact') ? new Contact($request->contact) : null);
        $order->setRelation('billing_address', $request->filled('billing_address') ? new Address($request->billing_address) : null);

        // calculate the order details
        $cartRepository = new CartRepository($order->toArray());

        // assign calculated value
        $order->setRelation('tax_lines', $cartRepository->tax_lines);
        $order->fill([
            'sub_total' => $cartRepository->sub_total,
            'tax_total' => $cartRepository->tax_total,
            'discount_total' => $cartRepository->discount_total,
            'grand_total' => $cartRepository->grand_total,
        ]);

        return response()->json($order, 200);
    }

    public function cart(Request $request)
    {
        return (new CartRepository(Order::find(optional($request)->order_id ?: 1033)->toArray()))->toArray();
    }
}
