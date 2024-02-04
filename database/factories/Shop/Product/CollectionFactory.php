<?php

namespace Database\Factories\Shop\Product;

use Illuminate\Database\Eloquent\Factories\Factory;

class CollectionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name' => ucfirst(fake()->words(rand(2, 4), true)),
            'conditions_type' => ['manual', 'automated'][rand(0, 1)],
        ];
    }
}
