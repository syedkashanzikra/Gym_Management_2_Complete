<?php

namespace App\Models;

use Coderstm\Traits\Core;
use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Template extends Model
{
    use Core;

    protected $dateTimeFormat = 'd M, Y \a\t h:i a';

    protected $fillable = [
        'label',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function schedules(): HasMany
    {
        return $this->hasMany(TemplateSchedule::class)->orderBy('day')->orderBy('start_at');
    }

    public function duplicate()
    {
        $template = $this->replicate()->fill([
            'label' => "[Duplicate] {$this->label}",
        ]);

        $template->save();

        $template->schedules()->saveMany($this->schedules->map(function ($item) {
            return $item->replicate([
                'template_id'
            ]);
        }));

        return $template->fresh();
    }

    public function getSchedulesByDayAttribute()
    {
        return $this->schedules->groupBy('day.value');
    }

    public function syncSchedules(Collection $schedules)
    {
        // delete removed schedules
        $this->schedules()->whereNotIn('id', $schedules->pluck('id')->filter())->delete();

        // create or updated new schedules
        $schedules->map(function ($item) {
            return (object) $item;
        })->each(function ($schedule) {
            $this->schedules()->updateOrCreate([
                'id' => optional($schedule)->id,
            ], [
                'day' => optional($schedule)->day,
                'start_at' => optional($schedule)->start_at,
                'end_at' => optional($schedule)->end_at,
                'class_id' => optional($schedule)->class ? optional($schedule)->class['id'] : null,
                'location_id' => optional($schedule)->location ? optional($schedule)->location['id'] : null,
                'instructor_id' => optional($schedule)->instructor ? optional($schedule)->instructor['id'] : null,
            ]);
        });

        return $this;
    }
}
