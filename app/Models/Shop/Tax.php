<?php

namespace App\Models\Shop;

use Carbon\Traits\Serialization;
use Coderstm\Traits\Logable;
use Coderstm\Traits\SerializeDate;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tax extends Model
{
    use HasFactory, Logable, SerializeDate;

    protected $fillable = [
        'country',
        'code',
        'state',
        'label',
        'compounded',
        'rate',
        'priority',
    ];

    protected $casts = [
        'compounded' => 'boolean',
    ];
}
