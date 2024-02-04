<?php

namespace App\Models\Shop\Order;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class DiscountLine extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'type',
        'value',
        'description',
    ];

    protected $hidden = [
        'discountable_type',
        'discountable_id',
    ];

    public function isFixedAmount(): bool
    {
        return $this->type == 'fixed_amount';
    }

    public function discountable()
    {
        return $this->morphTo();
    }
}
