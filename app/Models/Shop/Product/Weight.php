<?php

namespace App\Models\Shop\Product;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Weight extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'unit',
        'value',
    ];

    protected $hidden = [
        'weightable_type',
        'weightable_id',
    ];

    public function getValueAttribute($value)
    {
        return round($value, 3);
    }

    public function weightable()
    {
        return $this->morphTo();
    }
}
