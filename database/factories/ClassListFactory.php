<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ClassList>
 */
class ClassListFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'capacity' => rand(10, 20),
            'description' => fake()->paragraph(15),
            'is_active' => rand(0, 1),
            'has_description' => rand(0, 1),
            'urls' => [],
        ];
    }
}
