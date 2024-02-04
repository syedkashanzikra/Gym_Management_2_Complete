<?php

namespace Database\Factories\Shop\Product;

use Illuminate\Database\Eloquent\Factories\Factory;

class InventoryFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'available' => rand(-20, 20)
        ];
    }
}
