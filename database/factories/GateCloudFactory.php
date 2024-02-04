<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class GateCloudFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'door_name' => fake()->company(),
            'door_number' => rand(100, 500),
            'controller' => fake()->uuid(),
            'controller_socket' => rand(1, 2),
            'reader_type' => 'RC522',
        ];
    }
}
