<?php

namespace Database\Seeders;

use Coderstm\Models\Plan;
use Coderstm\Traits\Helpers;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class PlanSeeder extends Seeder
{
    use Helpers;

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $rows = $this->csvToArray(database_path("data/plans.csv"));
        foreach ($rows as $item) {
            $plan = Plan::create($item);
            $plan->syncFeatures(collect([
                [
                    'label' => 'Classes',
                    'description' => 'Maximum classes can be booked and join.',
                    'value' => $item['classes']
                ],
                [
                    'label' => 'Guest pass',
                    'description' => 'Allows non-members to try out the gym or studio facilities',
                    'value' => $item['guest']
                ]
            ]));
        }
    }
}
