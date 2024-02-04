<?php

namespace App\Models;

use Coderstm\Models\Admin as Model;

class Admin extends Model
{
    protected $dateTimeFormat = 'd M, Y \a\t h:i a';

    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'password',
        'phone_number',
        'is_supper_admin',
        'is_instructor',
        'is_active',
        'rfid',
    ];

    protected $appends = [
        'name',
        'guard',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'is_active' => 'boolean',
        'is_instructor' => 'boolean',
        'is_supper_admin' => 'boolean',
    ];
}
