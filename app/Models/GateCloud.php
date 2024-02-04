<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Sanctum\HasApiTokens;

class GateCloud extends Model
{
    use HasFactory, HasApiTokens;

    protected $fillable = [
        'door_number',
        'door_name',
        'controller',
        'controller_socket',
        'arguments',
        'reader_type',
        'token',
    ];
}
