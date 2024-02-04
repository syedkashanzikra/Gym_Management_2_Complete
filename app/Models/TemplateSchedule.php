<?php

namespace App\Models;

use Coderstm\Enum\AppDay;
use Coderstm\Traits\SerializeDate;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class TemplateSchedule extends Model
{
    use HasFactory, SerializeDate;

    protected $fillable = [
        'day',
        'start_at',
        'end_at',
        'class_id',
        'location_id',
        'instructor_id',
        'template_id',
    ];

    protected $hidden = [
        'class_id',
        'location_id',
        'instructor_id',
        'template_id',
    ];

    protected $casts = [
        'day' => AppDay::class,
    ];

    protected $with = [
        'class',
        'location',
        'instructor',
    ];

    public function class(): BelongsTo
    {
        return $this->belongsTo(ClassList::class, 'class_id');
    }

    public function location(): BelongsTo
    {
        return $this->belongsTo(Location::class);
    }

    public function instructor(): BelongsTo
    {
        return $this->belongsTo(Instructor::class);
    }
}
