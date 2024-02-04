<?php

namespace App\Models;

use Carbon\Carbon;
use Coderstm\Traits\Core;
use Illuminate\Database\Eloquent\Model;

class Announcement extends Model
{
    use Core;

    protected $dateTimeFormat = 'd M, Y \a\t h:i a';

    protected $fillable = [
        'date',
        'open_at',
        'close_at',
        'note',
    ];

    protected $casts = [
        'date' => 'datetime:Y-m-d',
    ];

    protected $appends = [
        'is_closed',
        'date_formated',
        'open_at_formated',
        'close_at_formated',
    ];

    public function getIsClosedAttribute()
    {
        return is_null($this->open_at) || is_null($this->close_at) || $this->open_at == $this->close_at;
    }

    public function getOpenAtFormatedAttribute()
    {
        if ($this->open_at) {
            return Carbon::parse($this->open_at)->format('h:i a');
        }
        return $this->open_at;
    }

    public function getDateFormatedAttribute()
    {
        return $this->date->format('d/m/Y');
    }

    public function getCloseAtFormatedAttribute()
    {
        if ($this->close_at) {
            return Carbon::parse($this->close_at)->format('h:i a');
        }
        return $this->close_at;
    }

    public function scopeActive($query)
    {
        return $query->whereDate('date', '>=', now())->orderBy('date', 'asc');
    }
}
