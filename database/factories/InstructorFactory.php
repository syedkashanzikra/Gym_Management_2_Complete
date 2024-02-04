<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Instructor>
 */
class InstructorFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'first_name' => fake()->firstName(),
            'last_name' => fake()->lastName(),
            'phone_number' => fake()->e164PhoneNumber(),
            'email' => fake()->safeEmail(),
            'description' => fake()->paragraph(15),
            'is_pt' => rand(0, 1),
            'hourspw' => rand(10, 25),
            'rentpw' => rand(10, 25),
            'status' => ['Active', 'Deactive', 'Hold'][rand(0, 2)],
            'urls' => [],
        ];
    }
}
