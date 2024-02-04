<?php

namespace App\Models\Shop\Product\Variant;

use Coderstm\Traits\Core;
use App\Models\Shop\Product\Variant;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use App\Models\Shop\Product\Option as ProductOption;
use Illuminate\Database\Eloquent\Casts\Attribute;

class Option extends Model
{
    use Core;

    protected $table = 'variant_options';

    protected $fillable = [
        'variant_id',
        'option_id',
        'position',
        'value',
    ];

    protected $hidden = [
        'attribute',
    ];

    protected $with = [
        'attribute',
    ];

    protected $appends = [
        'values',
    ];

    public function attribute()
    {
        return $this->belongsTo(ProductOption::class, 'option_id');
    }

    public function variant()
    {
        return $this->belongsTo(Variant::class);
    }

    protected function values(): Attribute
    {
        return Attribute::make(
            get: fn () => optional($this->attribute)->values ?? [],
        );
    }

    protected static function booted()
    {
        parent::booted();
        static::addGlobalScope('count', function (Builder $builder) {
            $builder->withMax('attribute as name', 'name');
            $builder->withMax('attribute as option_id', 'id');
        });
    }
}
