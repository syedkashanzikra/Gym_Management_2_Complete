<?php

namespace App\Models\Shop\Order;

use App\Models\Shop\Product\Weight;
use App\Models\Shop\Order\DiscountLine;
use App\Models\Shop\LineItem as BaseLineItem;

class LineItem extends BaseLineItem
{
    protected $fillable = [
        'title',
        'product_id',
        'variant_title',
        'variant_id',
        'sku',
        'price',
        'quantity',
        'taxable',
        'is_custom',
        'is_product_deleted',
        'is_variant_deleted',
    ];

    protected $with = [
        'weight',
        'discount',
        'product',
        'variant',
    ];

    protected $appends = [
        'thumbnail',
        'discounted_price',
        'has_discount',
        'total',
    ];

    protected $casts = [
        'is_product_deleted' => 'boolean',
        'is_variant_deleted' => 'boolean',
        'taxable' => 'boolean',
        'is_custom' => 'boolean',
    ];

    public function weight()
    {
        return $this->morphOne(Weight::class, 'weightable');
    }

    public function discount()
    {
        return $this->morphOne(DiscountLine::class, 'discountable');
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

    public function getHasDiscountAttribute()
    {
        return $this->hasDiscount();
    }

    protected static function booted()
    {
        static::created(function ($model) {
            $model->adjustInventory(-$model->quantity);
        });
        static::deleted(function ($model) {
            $model->adjustInventory($model->quantity);
        });
        static::updated(function ($model) {
            if ($model->isDirty('quantity')) {
                $model->adjustInventory($model->getOriginal('quantity') - $model->quantity);
            }
        });
        static::restored(function ($model) {
            $model->adjustInventory(-$model->quantity);
        });
    }
}
