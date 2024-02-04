<?php

namespace App\Models\Shop\Product;

use Coderstm\Traits\Core;
use App\Models\Shop\Product;
use App\Models\Shop\Location;
use Illuminate\Support\Facades\DB;
use App\Models\Shop\Product\Variant;
use Illuminate\Database\Eloquent\Model;

class Inventory extends Model
{
    use Core;

    protected $fillable = [
        'available',
        'active',
        'tracking',
        'incoming',
        'variant_id',
        'location_id',
    ];

    protected $casts = [
        'active' => 'boolean',
        'tracking' => 'boolean',
    ];

    protected $with = [
        // 'variant',
    ];

    protected $appends = [
        'trackable',
    ];

    public function variant()
    {
        return $this->belongsTo(Variant::class);
    }

    public function product()
    {
        return $this->hasOneThrough(Product::class, Variant::class);
    }

    public function location()
    {
        return $this->belongsTo(Location::class, 'location_id');
    }

    public function getTrackableAttribute()
    {
        return $this->active && $this->tracking;
    }

    public function scopeSortBy($query, $order_by = 'TITLE_ASC', $order_type = 'asc')
    {
        switch ($order_by) {
            case 'BEST_SELLING':
                // TODO: Need to Fix BEST_SELLING orderBy
                # code...
                break;

            case 'TITLE_DESC':
                $query->select('inventories.*')
                    ->leftJoin('variants', 'inventories.variant_id', '=', 'variants.id')
                    ->leftJoin('products', 'variants.product_id', '=', 'products.id')
                    ->addSelect(DB::raw('products.title AS product_title'))
                    ->groupBy('inventories.id')
                    ->orderBy('product_title', 'desc');
                break;

            case 'AVAILABLE_ASC':
                $query->orderBy('available', 'asc');
                break;

            case 'AVAILABLE_DESC':
                $query->orderBy('available', 'desc');
                break;

            case 'SKU_ASC':
                $query->select('inventories.*')
                    ->leftJoin('variants', 'inventories.variant_id', '=', 'variants.id')
                    ->addSelect(DB::raw('variants.sku AS variants_sku'))
                    ->groupBy('inventories.id')
                    ->orderBy('variants_sku', 'asc');
                break;

            case 'SKU_DESC':
                $query->select('inventories.*')
                    ->leftJoin('variants', 'inventories.variant_id', '=', 'variants.id')
                    ->addSelect(DB::raw('variants.sku AS variants_sku'))
                    ->groupBy('inventories.id')
                    ->orderBy('variants_sku', 'desc');
                break;

            case 'TITLE_ASC':
            default:
                $query->select('inventories.*')
                    ->leftJoin('variants', 'inventories.variant_id', '=', 'variants.id')
                    ->leftJoin('products', 'variants.product_id', '=', 'products.id')
                    ->addSelect(DB::raw('products.title AS product_title'))
                    ->groupBy('inventories.id')
                    ->orderBy('product_title', 'asc');
                break;
        }

        return $query;
    }

    public function scopeActive($query)
    {
        return $query->where('active', 1)->where('tracking', 1);
    }

    public function setAvailable($available = 0, $type = 'adjust')
    {
        $previousAvailable = $this->available;
        $newAvailable = $type == 'adjust' ? $previousAvailable + $available : $available;
        $this->available = $newAvailable;
        $this->save();
    }
}
