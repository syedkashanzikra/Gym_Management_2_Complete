<?php

namespace App\Models\Shop\Product;

use Coderstm\Traits\Logable;
use App\Models\Shop\Product;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;
use App\Models\Shop\Product\Attribute;
use Illuminate\Database\Eloquent\Model;
use App\Models\Shop\Product\Attribute\Value;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\Shop\Product\Variant\Option as VariantOption;
use Illuminate\Database\Eloquent\Casts\Attribute as CastsAttribute;

class Option extends Model
{
    use HasFactory, Logable, HasSlug;

    protected $fillable = [
        'name',
        'slug',
        'attribute_id',
        'product_id',
        'is_custom',
        'custom_values',
    ];

    protected $hidden = [
        'attribute_id',
        'attribue_values',
        'custom_values',
    ];

    protected $casts = [
        'custom_values' => 'json',
        'is_custom' => 'boolean',
    ];

    protected $appends = [
        'values',
    ];

    protected $with = [
        'attribue_values',
    ];

    public function attribute()
    {
        return $this->belongsTo(Attribute::class);
    }

    public function attribue_values()
    {
        return $this->belongsToMany(Value::class, 'option_values');
    }

    protected function values(): CastsAttribute
    {
        return CastsAttribute::make(
            get: function () {
                if ($this->is_custom) {
                    return $this->custom_values;
                } else {
                    return $this->attribue_values;
                }
            },
        );
    }

    public function getSlugOptions(): SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom('name')
            ->saveSlugsTo('slug')
            ->allowDuplicateSlugs();
    }

    public function variant_options()
    {
        return $this->hasMany(VariantOption::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function updateValue($value)
    {
        if (!empty($value)) {
            if ($this->is_custom) {
                if (!in_array($value, $this->custom_values)) {
                    $values = $this->custom_values;
                    array_push($values, [
                        'name' => $value,
                    ]);
                    $this->update([
                        'custom_values' => $values,
                    ]);
                }
            } else {
                $this->attribue_values()->attach(Value::firstOrCreate([
                    'name' => $value,
                    'attribute_id' => $this->attribute_id,
                ]));
            }
        }
    }
}
