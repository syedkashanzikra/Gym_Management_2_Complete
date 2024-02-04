<?php

namespace App\Models\Shop\Product;

use Coderstm\Traits\Core;
use App\Models\Shop\Product;
use Coderstm\Traits\Fileable;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;
use Illuminate\Database\Eloquent\Model;
use App\Models\Shop\Product\Collection\Condition;
use Illuminate\Database\Eloquent\Casts\Attribute;

class Collection extends Model
{
    use Core, HasSlug, Fileable;

    protected $fillable = [
        'name',
        'slug',
        'type',
        'status',
        'conditions_type',
        'description',
        'meta_title',
        'meta_keywords',
        'meta_description',
    ];

    public function products()
    {
        return $this->morphedByMany(Product::class, 'collectionable');
    }

    public function conditions()
    {
        return $this->hasMany(Condition::class);
    }

    public function setConditions(array $conditions)
    {
        $conditions = collect($conditions);

        // Delete removed conditions
        $this->conditions()->whereNotIn('id', $conditions->pluck('id')->filter())->each(function ($item) {
            $item->delete();
        });

        // Process new conditions
        foreach ($conditions as $condition) {
            $this->conditions()->updateOrCreate([
                'id' => $condition['id'],
            ], $condition);
        }
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
