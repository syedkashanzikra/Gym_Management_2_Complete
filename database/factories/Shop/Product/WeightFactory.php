<?php

namespace Database\Factories\Shop\Product;

use Illuminate\Database\Eloquent\Factories\Factory;

class WeightFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'unit' => ['kg', 'g', 'oz'][rand(0, 2)],
            'value' => rand(2, 5) / 2,
        ];
    }
}
