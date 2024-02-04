<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use Illuminate\Database\Seeder;
use Coderstm\Database\Seeders\TaskSeeder;
use Coderstm\Database\Seeders\UserSeeder;
use Coderstm\Database\Seeders\AdminSeeder;
use Coderstm\Database\Seeders\EnquirySeeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call([
            PlanSeeder::class,
            TaxSeeder::class,
            AdminSeeder::class,
            UserSeeder::class,
            ClassListSeeder::class,
            InstructorSeeder::class,
            LocationSeeder::class,
            TemplateSeeder::class,
            ClassScheduleSeeder::class,
            TemplateScheduleSeeder::class,
            EnquirySeeder::class,
            TaskSeeder::class,
            AnnouncementSeeder::class,
            ShopSeeder::class,
            OrderSeeder::class,
        ]);
    }
}
