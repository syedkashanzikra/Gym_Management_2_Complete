<?php

namespace App\Models;

use Coderstm\Traits\Core;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Booking extends Model
{
    use Core;

    protected $fillable = [
        'user_id',
        'schedule_id',
        'is_stand_by',
        'attendance',
        'status',
        'source',
        'canceled_at',
        'schedules_at',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'is_stand_by' => 'boolean',
        'attendance' => 'boolean',
        'status' => 'boolean',
        'source' => 'boolean',
        'canceled_at' => 'datetime:d M, Y \a\t h:i a',
        'schedules_at' => 'datetime:d M, Y',
        'created_at' => 'datetime:d M, Y \a\t h:i a',
        'updated_at' => 'datetime:d M, Y \a\t h:i a',
    ];

    protected $appends = [
        'same_day_canceled',
        'cancelable',
    ];

    protected $with = ['user'];

    public function getSameDayCanceledAttribute()
    {
        return !is_null($this->canceled_at) && $this->canceled_at->dayName == $this->created_at->dayName;
    }

    public function getCancelableAttribute()
    {
        $diffInHours = now()->diffInHours($this->schedules_at, false);
        if (is_null($this->canceled_at) && $this->schedules_at->isToday() && $diffInHours < 5 && $diffInHours > 0) {
            return 'late-cancellation';
        }
        return is_null($this->canceled_at) && $this->schedules_at->gte(now());
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function schedule(): BelongsTo
    {
        return $this->belongsTo(ClassSchedule::class, 'schedule_id');
    }

    public function scopeSortByUser($query)
    {
        return $query->leftJoin('users', 'users.id', '=', 'bookings.user_id')
            ->select('*', 'bookings.id as id')
            ->orderBy('users.first_name', 'asc');
    }

    public function scopeOnlyActive($query)
    {
        return $query->onlyNotCanceled()->where('is_stand_by', 0);
    }

    public function scopeOnlyNotCanceled($query)
    {
        return $query->whereNull('canceled_at');
    }

    public function scopeOnlyAttended($query)
    {
        return $query->onlyNotCanceled()
            ->whereHas('schedule', function ($q) {
                $q->whereNotNull('sign_off_at');
            })
            ->where('attendance', 1);
    }

    public function scopeOnlyNotAttended($query)
    {
        return $query->where('attendance', 0);
    }

    public function scopeOnlyCanceled($query)
    {
        return $query->whereNotNull('canceled_at');
    }

    public function scopeOnlyStandBy($query)
    {
        return $query->onlyNotCanceled()->where('is_stand_by', 1);
    }

    public function scopeOnlyNoShow($query)
    {
        return $query->onlyNotCanceled()
            ->onlyActive()
            ->where('attendance', 0)
            ->whereHas('schedule', function ($q) {
                $q->whereNotNull('sign_off_at');
            });
    }

    public function scopeOnlyLastWeekNoShow($query)
    {
        return $query->onlyNoShow()
            ->whereHas('schedule', function ($schedule) {
                $schedule->whereRaw('date_at BETWEEN ? AND ?', [now()->subDays(7)->format('Y-m-d'), now()->format('Y-m-d')])
                    ->whereNotNull('sign_off_at');
            });
    }

    public function scopeOnlyLateCancellation($query)
    {
        return $query->onlyActive()
            ->whereNotNull('canceled_at')
            ->whereRaw("DATE(schedules_at) = DATE(canceled_at)")
            ->whereRaw("TIMESTAMPDIFF(HOUR, canceled_at, schedules_at) < 5");
    }

    public function scopeOnlyLastWeekLateCancellation($query)
    {
        return $query->onlyLateCancellation()
            ->whereBetween('canceled_at', [now()->subDays(7), now()]);
    }

    public function cancel()
    {
        $this->schedule->logs()->create([
            'type' => 'booking-canceled',
            'message' => "<span class=\"mention\" data-user=\"{$this->user->id}\">{$this->user->name}</span> has been canceled."
        ]);
        return $this->update([
            'canceled_at' => now()
        ]);
    }

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            if (empty($model->schedules_at)) {
                $model->schedules_at = $model->schedule->date_at;
            }
        });
    }
}
