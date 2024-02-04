<?php

namespace App\Models\Shop;

use Coderstm\Traits\Core;
use Illuminate\Database\Eloquent\Model;

class Location extends Model
{
    use Core;

    protected $table = 'shop_locations';

    protected $fillable = [
        'name',
        'line1',
        'line2',
        'city',
        'country',
        'country_code',
        'state',
        'state_code',
        'postcode',
        'active',
    ];

    protected $casts = [
        'active' => 'boolean',
    ];

    protected $appends = [
        'address_label',
    ];

    public function scopeActive($query)
    {
        return $query->where('active', 1);
    }

    public function getAddressLabelAttribute()
    {
        return implode(', ', collect($this->attributes)->filter()->only([
            'line1',
            'line2',
            'city',
            'country',
            'country_code',
            'state',
            'state_code',
            'postcode',
        ])->toArray());
    }
}
