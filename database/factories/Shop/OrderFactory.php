<?php

namespace Database\Factories\Shop;

use App\Models\Shop\Location;
use Illuminate\Database\Eloquent\Factories\Factory;

class OrderFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'location_id' => Location::inRandomOrder()->first()->id,
            'collect_tax' => rand(0, 1),
        ];
    }
}
