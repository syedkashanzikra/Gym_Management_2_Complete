<?php

namespace App\Models;

use App\Models\User;
use Coderstm\Traits\Core;
use App\Events\GuestPassCreated;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class GuestPass extends Model
{
    use Core;

    protected $dateTimeFormat = 'd M, Y \a\t h:i a';

    protected $dispatchesEvents = [
        'created' => GuestPassCreated::class,
    ];

    protected $fillable = [
        'title',
        'first_name',
        'last_name',
        'email',
        'phone_number',
        'note',
    ];

    protected $appends = ['name'];

    protected $with = ['user'];

    public function getNameAttribute()
    {
        return "{$this->title} {$this->first_name} {$this->last_name}";
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class)->withOnly([]);
    }

    public function scopeWhereName($query, $filter)
    {
        return $query->where(DB::raw("CONCAT(`first_name`,`last_name`)"), 'like', "%{$filter}%");
    }

    public function scopeOnlyOwner($query)
    {
        return $query->whereHas('user', function ($q) {
            $q->where('id', current_user()->id);
        });
    }

    public function scopeSortBy($query, $column = 'CREATED_AT_ASC', $direction = 'asc')
    {
        switch ($column) {
            case 'name':
                $direction = $direction ?? 'asc';
                $query->orderByRaw("CONCAT(`first_name`, `first_name`) $direction");
                break;

            default:
                $query->orderBy($column ?: 'created_at', $direction ?? 'asc');
                break;
        }

        return $query;
    }

    protected static function booted()
    {
        parent::booted();
        static::creating(function ($model) {
            if (empty($model->user_id)) {
                $model->user_id = optional(current_user())->id;
            }
        });
    }
}
