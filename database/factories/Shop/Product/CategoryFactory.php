<?php

namespace Database\Factories\Shop\Product;

use Illuminate\Database\Eloquent\Factories\Factory;

class CategoryFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name' => ucfirst(fake()->words(rand(1, 2), true)),
            'description' => fake()->paragraph,
        ];
    }
}
