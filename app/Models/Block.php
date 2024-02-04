<?php

namespace App\Models;

use Coderstm\Traits\SerializeDate;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Block extends Model
{
    use HasFactory, SerializeDate;

    protected $fillable = [
        'release_at',
        'type',
        'disabled',
    ];

    protected $casts = [
        'release_at' => 'datetime:d/m/Y',
        'disabled' => 'boolean',
    ];

    protected $appends = [
        'active'
    ];

    public function getActiveAttribute()
    {
        return $this->is_active();
    }

    public function is_active()
    {
        return $this->release_at->gt(now());
    }

    /**
     * Scope a query to only include unblocked
     *
     * @param  \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeUnblocked($query)
    {
        return $query->whereDate('release_at', '<=', now())->orWhere('disabled', 1);
    }

    /**
     * Scope a query to only include blocked
     *
     * @param  \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeBlocked($query)
    {
        return $query->whereDate('release_at', '>', now())->where('disabled', 0);
    }
}
