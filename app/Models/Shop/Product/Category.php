<?php

namespace App\Models\Shop\Product;

use Coderstm\Traits\Core;
use App\Models\Shop\Product;
use Coderstm\Traits\Fileable;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use Core, HasSlug, Fileable;

    protected $fillable = [
        'name',
        'slug',
        'status',
        'description',
        'parent_id',
        'meta_title',
        'meta_keywords',
        'meta_description',
    ];

    public function products()
    {
        return $this->hasMany(Product::class);
    }

    public function parent()
    {
        return $this->belongsTo(static::class, 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(static::class, 'parent_id');
    }

    protected function description(): Attribute
    {
        return Attribute::make(
            set: fn ($value) => $value ?? '',
            get: fn ($value) => $value ?? '',
        );
    }

    public function getSlugOptions(): SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom('name')
            ->saveSlugsTo('slug')
            ->preventOverwrite();
    }
}
