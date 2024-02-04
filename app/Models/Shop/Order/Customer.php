<?php

namespace App\Models\Shop\Order;

use App\Models\Shop\Customer as ShopCustomer;

class Customer extends ShopCustomer
{
    protected $table = 'users';

    protected $appends = [
        'name',
    ];

    protected $with = [
        //
    ];
}
