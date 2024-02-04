<?php

namespace Database\Factories\Shop;

use Illuminate\Database\Eloquent\Factories\Factory;

class LocationFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name' => fake()->city,
            'line1' => fake()->streetAddress,
            'city' => fake()->city,
            'country' => fake()->country,
            'country_code' => fake()->countryCode,
            'state' => fake()->state,
            'postcode' => fake()->postcode,
        ];
    }
}
