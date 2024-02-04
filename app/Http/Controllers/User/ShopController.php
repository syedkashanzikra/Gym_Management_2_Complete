<?php

namespace App\Http\Controllers\User;

use App\Models\Shop\Product;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Http\Resources\Json\ResourceCollection;

class ShopController extends Controller
{
    public function products(Request $request, Product $product)
    {
        $product = $product->query();

        if ($request->filled('filter')) {
            $product->where('title', 'like', "%{$request->filter}%");
        }

        $product->onlyActive();

        $products = $product->sortBy(optional($request)->sortBy ?? 'created_at', optional($request)->direction ?? 'desc')
            ->paginate(optional($request)->rowsPerPage ?? 15);

        // Regular product results
        return new ResourceCollection($products);
    }

    public function product(Request $request, $slug)
    {
        $product = Product::with(['options'])->whereSlug($slug)->first();

        if (!$product) {
            abort(404, trans('messages.product_not_found'));
        }

        $response = $product->load('media')->toArray();

        if ($product->has_variant) {
            $varinat = $product->variants()->stockIn()->first();
            $response['options'] = $product->options->map(function ($option) use ($varinat) {
                return array_merge($option->toArray(), [
                    'value' => optional($varinat->options->firstWhere('name', $option->name))->value
                ]);
            });
            $response['price_formated'] = $varinat->price_formated;
            $response['variant_id'] = $varinat->id;
            $response['price'] = $varinat->price;
            $response['in_stock'] = $varinat->in_stock;
            $response['variants'] = $product->variants->map(function ($varinat) {
                return $varinat->only([
                    'compare_at_price',
                    'id',
                    'in_stock',
                    'media_id',
                    'price',
                    'price_formated',
                    'thumbnail',
                    'title',
                ]);
            });
        } else {
            $varinat = $product->default_variant;
            $response['price_formated'] = $varinat->price_formated;
            $response['variant_id'] = $varinat->id;
            $response['price'] = $varinat->price;
            $response['in_stock'] = $varinat->in_stock;
        }

        return response()->json($response, 200);
    }
}
