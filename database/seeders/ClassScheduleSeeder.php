<?php

namespace Database\Seeders;

use App\Models\Booking;
use App\Models\ClassSchedule;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class ClassScheduleSeeder extends Seeder
{
    // use WithoutModelEvents;

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        ClassSchedule::factory()->count(100)
            ->has(Booking::factory()->count(rand(8, 10)))
            ->create();
    }
}
