<?php

namespace App\Http\Controllers\Admin\Shop\Product;

use Illuminate\Http\Request;
use App\Models\Shop\Product\Vendor;
use App\Http\Controllers\Controller;

class VendorController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, Vendor $vendor)
    {
        $vendor = $vendor->query();

        if ($request->filled('filter')) {
            $vendor->where('name', 'like', "%{$request->filter}%");
        }

        if ($request->boolean('deleted')) {
            $vendor->onlyTrashed();
        }

        $vendor = $vendor->orderBy($request->sortBy ?? 'created_at', $request->direction ?? 'desc')
            ->paginate($request->rowsPerPage ?? 15);
        return response()->json($vendor, 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, Vendor $vendor)
    {
        $rules = [
            'name' => 'required',
        ];

        $this->validate($request, $rules);

        $vendor = $vendor->create($request->input());

        return response()->json([
            'data' => $vendor->fresh(),
            'message' => 'Vendor has been created successfully!'
        ], 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Shop\Product\Vendor $vendor
     * @return \Illuminate\Http\Response
     */
    public function show(Vendor $vendor)
    {
        return response()->json($vendor, 200);
    }
}
