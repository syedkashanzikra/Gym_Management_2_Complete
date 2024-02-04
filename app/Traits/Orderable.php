<?php

namespace App\Traits;

use App\Models\Shop\Order;

trait Orderable
{
    public function orders()
    {
        return $this->hasMany(Order::class, 'customer_id');
    }

    public function latestOrder()
    {
        return $this->hasOne(Order::class, 'customer_id')->latestOfMany();
    }
}
