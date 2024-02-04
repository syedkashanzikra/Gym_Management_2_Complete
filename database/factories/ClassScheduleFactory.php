<?php

namespace Database\Factories;

use Carbon\Carbon;
use App\Models\Location;
use App\Models\ClassList;
use App\Models\ClassSchedule;
use App\Models\Instructor;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Instructor>
 */
class ClassScheduleFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        $start_at = fake()->time('H:i');
        $end_at = Carbon::parse($start_at)->addMinutes(30, 60)->format('H:i');
        return [
            'day' => ['Monday',  'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'][rand(0, 6)],
            'start_at' => $start_at,
            'end_at' => $end_at,
            'start_of_week' => Carbon::parse(fake()->dateTimeBetween('-2 weeks', '+2 weeks'))->startOfWeek()->format('Y-m-d'),
            'class_id' => ClassList::inRandomOrder()->first()->id,
            'location_id' => Location::inRandomOrder()->first()->id,
            'instructor_id' => Instructor::inRandomOrder()->first()->id,
        ];
    }

    public function configure()
    {
        return $this->afterCreating(function (ClassSchedule $classSchedule) {
            $classSchedule->update([
                'sign_off_at' => $classSchedule->date_at->lte(now()) ? $classSchedule->date_at : null
            ]);
        });
    }
}
