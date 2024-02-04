<?php

namespace App\Http\Controllers\Admin;

use App\Models\Shop\Tax;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class TaxController extends Controller
{
    public function index(Request $request, Tax $tax)
    {
        $tax = $tax->query();
        return $tax->orderBy($request->sortBy ?? 'code', $request->direction ?? 'desc')
            ->orderBy('priority')
            ->get();
    }

    public function store(Request $request, Tax $tax)
    {
        $rules = [
            'country'  => 'required',
            'code'  => 'required',
            'state'  => 'required',
            'label'  => 'required',
            'rate'  => 'required',
        ];

        // Validate those rules
        $this->validate($request, $rules);

        // create the tax
        $tax = Tax::create($request->input());

        return response()->json([
            'data' => $tax,
            'message' => trans_module('store', 'tax'),
        ], 200);
    }

    public function update(Request $request, Tax $tax)
    {
        $rules = [
            'country'  => 'required',
            'code'  => 'required',
            'state'  => 'required',
            'label'  => 'required',
            'rate'  => 'required',
        ];

        // Validate those rules
        $this->validate($request, $rules);

        // update the tax
        $tax->update($request->input());

        return response()->json([
            'data' => $tax->fresh(),
            'message' => trans_module('updated', 'tax'),
        ], 200);
    }

    public function destroy(Tax $tax)
    {
        $tax->delete();
        return response()->json([
            'message' => trans_module('destroy', 'tax'),
        ], 200);
    }
}
