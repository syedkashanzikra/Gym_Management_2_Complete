<?php

namespace App\Models\Shop\Order;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TaxLine extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'label',
        'rate',
        'amount',
        'type',
    ];

    protected $hidden = [
        'taxable_type',
        'taxable_id',
    ];

    public function taxable()
    {
        return $this->morphTo();
    }
}
