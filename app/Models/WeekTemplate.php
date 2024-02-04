<?php

namespace App\Models;

use App\Models\Template;
use App\Models\ClassList;
use Coderstm\Traits\Logable;
use App\Models\ClassSchedule;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class WeekTemplate extends Model
{
    use HasFactory, Logable;

    public $timestamps = false;

    protected $fillable = [
        'start_of_week',
        'template_id',
    ];

    protected $hidden = ['template_id'];

    protected $casts = [
        'start_of_week' => 'datetime:Y-m-d',
    ];

    protected $appends = ['start_of_week_formated'];

    protected $with = ['template'];

    public function template(): BelongsTo
    {
        return $this->belongsTo(Template::class);
    }

    public function getStartOfWeekFormatedAttribute()
    {
        return $this->start_of_week->format('d/m/Y');
    }

    public function scopeOnlyActive($query)
    {
        return $query->where('start_of_week', '>=', now()->startOfWeek());
    }

    public static function assignClassSchedule(array $weeks = [])
    {
        collect($weeks)->each(function ($item) {
            $weekTemplate = static::updateOrCreate([
                'start_of_week' => $item['start_of_week'],
            ], [
                'template_id' => isset($item['template']['id']) ? $item['template']['id'] : null
            ]);
            if ($weekTemplate->wasRecentlyCreated || $weekTemplate->wasChanged('template_id')) {
                ClassSchedule::has('template')->where('start_of_week', $item['start_of_week'])->whereNull('sign_off_at')->forceDelete();
                if (isset($item['template']['id'])) {
                    $template = Template::find($item['template']['id']);
                    $template->schedules()->each(function ($schedule) use ($item) {
                        ClassSchedule::create([
                            'day' => $schedule->day ? $schedule->day->value : null,
                            'start_of_week' => $item['start_of_week'],
                            'start_at' => $schedule->start_at,
                            'end_at' => $schedule->end_at,
                            'class_id' => $schedule->class_id,
                            'capacity' => optional(ClassList::find($schedule->class_id))->capacity,
                            'location_id' => $schedule->location_id,
                            'instructor_id' => $schedule->instructor_id,
                            'template_id' => $schedule->template_id
                        ]);
                    });
                }
            }
        });
    }
}
