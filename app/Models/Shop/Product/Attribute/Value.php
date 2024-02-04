<?php

namespace App\Models\Shop\Product\Attribute;

use Coderstm\Traits\Core;
use Coderstm\Traits\Fileable;
use App\Models\Shop\Product\Attribute;
use Illuminate\Database\Eloquent\Model;

class Value extends Model
{
    use Core, Fileable;

    protected $table = 'attribute_values';

    protected $casts = [
        'has_thumbnail' => 'boolean',
    ];

    protected $fillable = [
        'name',
        'has_thumbnail',
        'color',
        'thumbnail_id',
        'attribute_id',
    ];

    protected $with = [
        // 'media',
        // 'attribute',
    ];

    public function attribute()
    {
        return $this->belongsTo(Attribute::class);
    }
}
