<?php

namespace App\Http\Controllers\Admin\Shop;

use Illuminate\Http\Request;
use App\Models\Shop\Customer;
use App\Http\Controllers\Controller;

class CustomerController extends Controller
{
    public function show(Customer $customer)
    {
        return response()->json($customer->load([
            'address',
            'latestOrder',
        ]), 200);
    }

    public function update(Request $request, Customer $customer)
    {
        // Set rules
        $rules = [
            'first_name' => 'required',
            'last_name' => 'required',
            'email' => 'email|unique:users,email,' . $customer->id,
        ];

        // Validate those rules
        $this->validate($request, $rules);

        $customer->update($request->input());

        $customer->updateOrCreateAddress($request->input('address'));

        return response()->json([
            'data' => $customer->fresh([
                'address',
                'latestOrder',
            ]),
            'message' => 'Customer has been updated successfully!',
        ], 200);
    }
}
