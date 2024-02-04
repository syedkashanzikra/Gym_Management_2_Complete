<?php

namespace App\Http\Controllers\Admin\Shop\Product;

use App\Services\Resource;
use App\Models\Shop\Product;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Shop\Product\Variant;
use Illuminate\Http\Resources\Json\ResourceCollection;

class VariantController extends Controller
{
    public function index(Request $request, Product $product)
    {
        $variant = $product->variants()->with('inventories', 'inventories.location:id,name', 'options')->where('is_default', 0);

        if ($request->input('deleted') ? $request->boolean('deleted') : false) {
            $variant->onlyTrashed();
        }

        $variant->orderBy($request->sortBy ?? 'created_at', $request->direction ?? 'desc');

        if ($request->filled('rowsPerPage')) {
            return new ResourceCollection($variant->paginate($request->rowsPerPage ?? 50));
        } else {
            return new ResourceCollection($variant->get());
        }
    }

    public function show(Variant $variant)
    {
        return response()->json($variant, 200);
    }

    public function update(Request $request, Variant $variant)
    {
        // Set rules
        $rules = [
            'barcode' => "unique:variants,barcode,{$variant->id}",
        ];

        // Validate those rules
        $this->validate($request, $rules);

        // Update variant
        $variant->update($request->input());

        $variant->saveRelated(new Resource($request->input()));

        return response()->json([
            'data' => $variant->fresh(),
            'message' => 'Variant has been updated successfully!',
        ], 200);
    }
}
