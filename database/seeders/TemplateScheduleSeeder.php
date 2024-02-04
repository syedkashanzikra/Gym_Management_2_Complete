<?php

namespace Database\Seeders;

use App\Models\Template;
use App\Models\WeekTemplate;
use Illuminate\Database\Seeder;
use Coderstm\Traits\Helpers;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class TemplateScheduleSeeder extends Seeder
{
    use Helpers;

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $weeks = $this->weeksBetweenTwoDates(now()->startOfWeek(), now()->addMonth()->endOfWeek()->addWeek());
        WeekTemplate::assignClassSchedule(collect($weeks)->map(function ($item) {
            return [
                'start_of_week' => $item,
                'template' => Template::inRandomOrder()->first()->toArray()
            ];
        })->toArray());
    }
}
