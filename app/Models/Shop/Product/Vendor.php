<?php

namespace App\Models\Shop\Product;

use Coderstm\Traits\Core;
use App\Models\Shop\Product;
use Illuminate\Database\Eloquent\Model;

class Vendor extends Model
{
    use Core;

    protected $fillable = [
        'name',
    ];

    public function products()
    {
        return $this->hasMany(Product::class);
    }
}
