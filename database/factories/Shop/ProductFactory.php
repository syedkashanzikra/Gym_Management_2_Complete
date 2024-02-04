<?php

namespace Database\Factories\Shop;

use App\Models\Shop\Product\Category;
use App\Models\Shop\Product\Vendor;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'title' => fake()->sentence,
            'description' => fake()->paragraph,
            'has_variant' => rand(0, 1),
            'category' => Category::inRandomOrder()->first()->toArray(),
            'vendor' => Vendor::inRandomOrder()->first()->toArray(),
        ];
    }
}
