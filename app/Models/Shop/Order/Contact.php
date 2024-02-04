<?php

namespace App\Models\Shop\Order;

use Illuminate\Database\Eloquent\Model;

class Contact extends Model
{
    protected $table = 'order_contacts';

    public $timestamps = false;

    protected $fillable = [
        'email',
        'phone_number',
    ];

    protected $hidden = [
        'contactable_type',
        'contactable_id',
    ];

    public function contactable()
    {
        return $this->morphTo();
    }
}
