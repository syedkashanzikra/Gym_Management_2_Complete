<?php

namespace Database\Seeders;

use App\Models\Location;
use Coderstm\Traits\Helpers;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class LocationSeeder extends Seeder
{
    use Helpers;

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $rows = $this->csvToArray(base_path("database/data/locations.csv"));
        foreach ($rows as $item) {
            Location::updateOrCreate([
                'label' => $item['label'],
            ]);
        }
    }
}
