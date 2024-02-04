<?php

namespace Database\Seeders;

use App\Models\Shop\Tax;
use League\ISO3166\ISO3166;
use Coderstm\Traits\Helpers;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class TaxSeeder extends Seeder
{
    use Helpers;

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $country = (new ISO3166)->name(config('app.country'));
        if ($country) {
            Tax::updateOrCreate([
                'country' => config('app.country'),
                'label' => 'VAT',
                'code' => $country['alpha2'],
                'state' => '*',
                'rate' => 10,
                'priority' => 0,
            ]);
        }

        Tax::updateOrCreate([
            'country' => 'Rest of world',
            'label' => 'VAT',
            'code' => '*',
            'state' => '*',
            'rate' => 0,
            'priority' => 0,
        ]);
    }
}
