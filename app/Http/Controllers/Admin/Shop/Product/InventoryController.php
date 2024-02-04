<?php

namespace App\Http\Controllers\Admin\Shop\Product;

use App\Http\Controllers\Controller;
use Illuminate\Http\Resources\Json\ResourceResponse;
use Illuminate\Http\Resources\Json\ResourceCollection;
use App\Models\Shop\Location as ShopLocation;
use App\Models\Shop\Product\Inventory;
use Illuminate\Http\Request;

class InventoryController extends Controller
{
    public function index(Request $request, Inventory $inventory)
    {
        $request->merge([
            'location' => $request->location ?? optional(ShopLocation::active()->first())->id
        ]);

        $inventories = $inventory->with('variant.product')->active();

        $inventories->whereHas('variant', function ($query) use ($request) {
            $query->onlyTrackInventory();

            $query->whereHas('product', function ($query) use ($request) {
                if ($request->filled('filter')) {
                    $query->where('title', 'like', "%{$request->filter}%");
                }

                if ($request->filled('collection')) {
                    $query->whereHas('collections', function ($query) use ($request) {
                        $query->where('id', $request->collection);
                    });
                }

                if ($request->filled('category')) {
                    $query->whereHas('category', function ($query) use ($request) {
                        $query->where('id', $request->category);
                    });
                }

                if ($request->filled('vendor')) {
                    $query->whereHas('vendor', function ($query) use ($request) {
                        $query->where('id', $request->vendor);
                    });
                }

                if ($request->filled('tag')) {
                    $query->whereHas('tags', function ($query) use ($request) {
                        $query->where('id', $request->tag);
                    });
                }
            });

            if ($request->filled('filter')) {
                $query->orWhere('sku', 'like', "%{$request->filter}%");
            }
        });

        $inventories->whereHas('location', function ($query) use ($request) {
            $query->active()->where('id', $request->location);
        });

        if ($request->boolean('deleted')) {
            $inventories->onlyTrashed();
        }

        $inventories = $inventories->sortBy($request->sortBy ?? 'created_at', $request->direction ?? 'desc')
            ->paginate($request->rowsPerPage ?? 15);

        return new ResourceCollection($inventories);
    }

    public function update(Request $request, Inventory $inventory)
    {
        // Set rules
        $rules = [
            'available' => 'required',
        ];

        // Validate those rules
        $this->validate($request, $rules);

        $inventory->update([
            'available' => $request->available,
        ]);

        return response()->json([
            'data' => new ResourceResponse($inventory->load([
                'variant',
                'variant.product',
            ])),
            'message' => 'Inventory has been updated successfully!',
        ], 200);
    }
}
