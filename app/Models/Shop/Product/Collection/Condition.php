<?php

namespace App\Models\Shop\Product\Collection;

use Illuminate\Database\Eloquent\Model;
use Coderstm\Traits\Core;

class Condition extends Model
{
    use Core;

    protected $table = 'collection_conditions';

    protected $fillable = [
        'type',
        'relation',
        'value',
    ];
}
