<?php

namespace Database\Seeders;

use App\Models\Template;
use Illuminate\Database\Seeder;
use App\Models\TemplateSchedule;
use Coderstm\Traits\Helpers;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class TemplateSeeder extends Seeder
{
    use Helpers;

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $templates =  [
            'Christmas Timetable',
            'March ' . date('Y'),
            'April ' . date('Y'),
            'June ' . date('Y'),
            'December ' . date('Y'),
            'General Timetable',
        ];

        foreach ($templates as $template) {
            Template::factory()
                ->has(TemplateSchedule::factory()->count(rand(25, 30)), 'schedules')
                ->create([
                    'label' => $template
                ]);
        }
    }
}
