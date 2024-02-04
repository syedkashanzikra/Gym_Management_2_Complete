<?php

namespace App\Models\Shop\Cart;

use App\Models\Shop\Order\DiscountLine;
use Illuminate\Database\Eloquent\Model;

class LineItem extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'id',
        'price',
        'quantity',
        'taxable',
        'discount',
        'title',
        'product_id',
        'variant_title',
        'variant_id',
        'sku',
        'is_custom',
        'is_product_deleted',
        'is_variant_deleted',
    ];

    protected $appends = [
        'discounted_price',
        'total',
    ];

    public function getDiscountAttribute($value)
    {
        return new DiscountLine($value ?: []);
    }

    public function hasDiscount(): bool
    {
        return !is_null($this->discount) ?: false;
    }

    public function getDiscountedPriceAttribute()
    {
        if ($this->hasDiscount()) {
            if ($this->discount->isFixedAmount()) {
                $price = $this->price - $this->discount->value;
            } else {
                $price = round($this->price - ($this->price * $this->discount->value) / 100, 2);
            }
            return $price > 0 ? $price : 0;
        }
        return $this->price;
    }

    public function getTotalAttribute()
    {
        return round($this->discounted_price * $this->quantity, 2);
    }
}
