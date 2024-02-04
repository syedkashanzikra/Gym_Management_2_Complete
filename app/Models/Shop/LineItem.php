<?php

namespace App\Models\Shop;

use Coderstm\Traits\Core;
use App\Models\Shop\Product;
use App\Models\Shop\Product\Variant;
use App\Models\Shop\Product\Inventory;
use Illuminate\Database\Eloquent\Model;

class LineItem extends Model
{
    use Core;

    protected $hidden = [
        'itemable_type',
        'itemable_id',
    ];

    protected $casts = [
        'is_product_deleted' => 'boolean',
        'is_variant_deleted' => 'boolean',
        'taxable' => 'boolean',
        'is_custom' => 'boolean',
    ];

    protected $with = [
        'product',
        'variant',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id')->withOnly('media');
    }

    public function variant()
    {
        return $this->belongsTo(Variant::class, 'variant_id')->withOnly(['media', 'options']);
    }

    public function itemable()
    {
        return $this->morphTo();
    }

    public function getThumbnailAttribute($thumbnail = null)
    {
        if (isset($this->variant->thumbnail)) {
            return $this->variant->thumbnail;
        } else if (isset($this->product->thumbnail)) {
            return $this->product->thumbnail;
        } else {
            return $thumbnail;
        }
    }

    public function adjustInventory($quantity)
    {
        $inventory = Inventory::where([
            'location_id' => $this->itemable->location_id,
            'variant_id' => $this->variant_id,
        ])->first();

        if ($inventory) {
            $inventory->setAvailable($quantity);
        }
        return $inventory;
    }
}
