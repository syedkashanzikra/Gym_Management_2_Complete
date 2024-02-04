<?php

namespace Database\Factories\Shop\Product;

use Illuminate\Database\Eloquent\Factories\Factory;

class VariantFactory extends Factory
{

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $price = rand(100, 300);
        return [
            'price' => $price,
            'cost_per_item' => $price - 30,
            'track_inventory' => rand(0, 1),
            'out_of_stock_track_inventory' => rand(0, 1),
        ];
    }
}
