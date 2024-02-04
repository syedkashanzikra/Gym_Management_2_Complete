<?php

namespace App\Models\Shop\Product;

use Coderstm\Traits\Core;
use App\Models\Shop\Product;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;
use Illuminate\Database\Eloquent\Model;

class Tag extends Model
{
    use Core, HasSlug;

    protected $fillable = [
        'name',
        'slug',
    ];

    public function products()
    {
        return $this->morphedByMany(Product::class, 'taggable');
    }

    public function getSlugOptions(): SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom('name')
            ->saveSlugsTo('slug')
            ->preventOverwrite();
    }
}
