<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Announcement>
 */
class AnnouncementFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        $close = rand(0, 1);
        return [
            'date' => now()->addDays(rand(1, 365)),
            'open_at' => $close ? null : '08:00',
            'close_at' => $close ? null : '16:00',
            'note' => fake()->paragraph(1),
        ];
    }
}
