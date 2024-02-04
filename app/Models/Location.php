<?php

namespace App\Models;

use Coderstm\Traits\Core;
use Illuminate\Database\Eloquent\Model;

class Location extends Model
{
    use Core;

    protected $dateTimeFormat = 'd M, Y \a\t h:i a';

    protected $fillable = [
        'label',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];
}
