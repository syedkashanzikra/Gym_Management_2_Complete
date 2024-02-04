<?php

namespace App\Models;

use App\Models\Shop\Order;
use Coderstm\Models\Enquiry as Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Enquiry extends Model
{
    protected $fillable = [
        'name',
        'email',
        'phone',
        'subject',
        'message',
        'status',
        'seen',
        'is_archived',
        'user_archived',
        'order_id',
        'source',
    ];

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class)->withOnly(['line_items']);
    }

    public function admin(): BelongsTo
    {
        return $this->belongsTo(Admin::class)->withOnly([]);
    }

    protected static function booted()
    {
        parent::booted();
        static::creating(function ($model) {
            if (is_admin()) {
                $model->admin_id = optional(current_user())->id;
            }
        });
    }
}
