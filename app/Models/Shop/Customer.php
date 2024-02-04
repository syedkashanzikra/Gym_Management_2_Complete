<?php

namespace App\Models\Shop;

use App\Models\User;
use App\Traits\Orderable;
use Illuminate\Database\Eloquent\Builder;

class Customer extends User
{
    use Orderable;

    protected $table = 'users';

    protected $dateTimeFormat = 'd M, Y';

    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'phone_number',
        'dob',
        'gender',
        'note',
        'email_marketing',
        'collect_tax',
        'is_active',
    ];

    protected $casts = [
        'email_marketing' => 'boolean',
        'is_active' => 'boolean',
        'collect_tax' => 'boolean',
    ];

    protected $appends = [
        'name',
        'average_order_amount',
    ];

    protected $with = [
        'address',
        'latestOrder',
    ];

    public function getMorphClass()
    {
        return 'User';
    }

    public function getAverageOrderAmountAttribute()
    {
        if ($this->total_orders > 0) {
            return round($this->total_spent / $this->total_orders);
        } else {
            return 0;
        }
    }

    public function getNameAttribute(): string
    {
        return "{$this->first_name} {$this->last_name}";
    }

    protected static function booted()
    {
        parent::booted();
        static::addGlobalScope('default', function (Builder $builder) {
            $builder->withSum('orders as total_spent', 'grand_total')
                ->withCount('orders as total_orders');
        });
    }
}
