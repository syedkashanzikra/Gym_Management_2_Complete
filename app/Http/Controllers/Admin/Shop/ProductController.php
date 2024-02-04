<?php

namespace App\Http\Controllers\Admin\Shop;

use App\Models\Shop\Product;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Shop\Product\Variant;
use Illuminate\Http\Resources\Json\ResourceCollection;
use App\Http\Resources\Product\Variant\VariantResource;

class ProductController extends Controller
{
    public function index(Request $request, Product $product)
    {
        $products = $product->with($request->includes ?? [
            'default_variant',
            'vendor',
            'category',
        ]);

        if ($request->filled('filter')) {
            $products->where('title', 'like', "%{$request->filter}%");
        }

        if ($request->filled('status')) {
            $products->where('status', $request->status);
        }

        if ($request->filled('collection')) {
            $products->whereHas('collections', function ($query) use ($request) {
                $query->where('id', $request->collection);
            });
        }

        if ($request->filled('category')) {
            $products->whereHas('category', function ($query) use ($request) {
                $query->where('id', $request->category);
            });
        }

        if ($request->filled('vendor')) {
            $products->whereHas('vendor', function ($query) use ($request) {
                $query->where('id', $request->vendor);
            });
        }

        if ($request->filled('tag')) {
            $products->whereHas('tags', function ($query) use ($request) {
                $query->where('id', $request->tag);
            });
        }

        if ($request->filled('location')) {
            $products->whereHas('inventories', function ($query) use ($request) {
                $query->whereHas('location', function ($query) use ($request) {
                    $query->active()->where('id', $request->location);
                });
            });
        }

        if ($request->filled('conditions')) {
            $products->filters($request->conditions, $request->conditions_type ?: 'any');
        }

        if ($request->boolean('deleted')) {
            $products->onlyTrashed();
        }

        $products = $products->sortBy($request->sortBy, $request->direction)
            ->paginate($request->rowsPerPage ?? 15);

        // Regular product results
        return new ResourceCollection($products);
    }

    public function store(Request $request, Product $product)
    {
        // Set rules
        $rules = [
            'title' => 'required',
            // Images
            'media' => 'array',
            'media.*.id' => 'sometimes|required_unless:media.*.src,null|integer',
            'media.*.src' => 'required_if:media.*.id,null|string',
            // Tags
            'tags' => 'array',
            'tags.*.id' => 'sometimes|required_unless:tags.*.name,null|integer',
            'tags.*.name' => 'required_if:tags.*.id,null|string',
            // Options
            'options' => 'array',
            // 'options.*.name' => 'required|string',
            // 'options.*.is_custom' => 'required|boolean',
            // 'options.*.attribute_id' => 'required_if:options.*.is_custom,0|integer',
            // 'options.*.values' => 'required|array',
            // 'options.*.values.*.id' => 'integer',
            // 'options.*.values.*.name' => 'required|string',
        ];

        // Validate those rules
        $this->validate($request, $rules);

        $product = $product->create($request->input());

        // save product's realted model
        $this->saveRelated($request, $product);

        return response()->json([
            'data' => $product->fresh([
                'vendor',
                'category',
                'tags',
                'collections',
                'variants',
                'default_variant',
                'options',
                'media',
            ]),
            'message' => trans_module('store', 'product'),
        ], 200);
    }

    public function show(Product $product)
    {
        return response()->json($product->load([
            'vendor',
            'category',
            'tags',
            'collections',
            'variants',
            'default_variant',
            'options',
            'media',
        ]), 200);
    }

    public function update(Request $request, Product $product)
    {
        // Set rules
        $rules = [
            'title' => 'required',
            // Images
            'media' => 'array',
            'media.*.id' => 'sometimes|required_unless:media.*.src,null|integer',
            'media.*.src' => 'required_if:media.*.id,null|string',
            // Tags
            'tags' => 'array',
            'tags.*.id' => 'sometimes|required_unless:tags.*.name,null|integer',
            'tags.*.name' => 'required_if:tags.*.id,null|string',
            // Vendor
            'vendor' => 'array|nullable',
            'vendor.id' => 'sometimes|required_unless:vendor.name,null|integer',
            'vendor.name' => 'sometimes|required_if:vendor.id,null|string',
            // Options
            'options' => 'array',
            // 'options.*.name' => 'required|string',
            // 'options.*.is_custom' => 'required|boolean',
            // 'options.*.attribute_id' => 'required_if:options.*.is_custom,0|integer',
            // 'options.*.values' => 'required|array',
            // 'options.*.values.*.id' => 'integer',
            // 'options.*.values.*.name' => 'required|string',
        ];

        // Validate those rules
        $this->validate($request, $rules);

        $product->update($request->input());

        // save product's realted model
        $this->saveRelated($request, $product);

        return response()->json([
            'data' => $product->fresh([
                'vendor',
                'category',
                'tags',
                'collections',
                'variants',
                'default_variant',
                'options',
                'media',
            ]),
            'message' => trans_module('updated', 'product'),
        ], 200);
    }

    public function destroy(Product $product)
    {
        $product->delete();
        return response()->json([
            'message' => trans_module('destroy', 'product'),
        ], 200);
    }

    public function destroySelected(Request $request, Product $product)
    {
        $this->validate($request, [
            'items' => 'required',
        ]);
        $product->whereIn('id', $request->items)->each(function ($item) {
            $item->delete();
        });
        return response()->json([
            'message' => trans_modules('destroy', 'product'),
        ], 200);
    }

    public function restore($id)
    {
        Product::onlyTrashed()
            ->where('id', $id)->each(function ($item) {
                $item->restore();
            });
        return response()->json([
            'message' => trans_module('restored', 'product'),
        ], 200);
    }

    public function restoreSelected(Request $request, Product $product)
    {
        $this->validate($request, [
            'items' => 'required',
        ]);
        $product->onlyTrashed()
            ->whereIn('id', $request->items)->each(function ($item) {
                $item->restore();
            });
        return response()->json([
            'message' => trans_modules('restored', 'product'),
        ], 200);
    }

    public function findByBarCode($barcode)
    {
        $variant = Variant::where('barcode', $barcode)->firstOrFail();
        return response()->json($variant->load('product'), 200);
    }

    protected function saveRelated(Request $request, Product $product)
    {
        // Update media
        if ($request->filled('media')) {
            $product->syncMedia($request->input('media'));
        }

        // Update options
        if ($request->filled('options')) {
            $product->setOptions($request->input('options'));
        }

        // Update collections
        if ($request->filled('collections')) {
            $product->setCollections($request->input('collections'));
        }

        // Update tags
        if ($request->filled('tags')) {
            $product->setTags($request->input('tags'));
        }

        // Update default_variant
        if ($request->filled('default_variant')) {
            $product->setDeafultVariant($request->input('default_variant'));
        }

        // Update variants
        if ($request->filled('variants')) {
            $product->setVariants($request->input('variants'));
        }
    }
}
