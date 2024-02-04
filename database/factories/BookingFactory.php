<?php

namespace Database\Factories;

use App\Models\User;
use App\Models\Booking;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class BookingFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'user_id' => optional(User::inRandomOrder()->first())->id,
            'status' => rand(0, 1),
            'source' => rand(0, 1),
        ];
    }

    public function configure()
    {
        return $this->afterCreating(function (Booking $booking) {
            $existingBooking = Booking::where('schedule_id', $booking->schedule_id)->where('user_id', $booking->user_id)
                ->count();
            if ($existingBooking == 1) {
                if (boolval(rand(0, 1))) {
                    $booking->update([
                        'canceled_at' => $booking->schedules_at->subDays(rand(0, 2))
                    ]);
                }
                if ($booking->schedules_at->lte(now())) {
                    $booking->update([
                        'attendance' => true
                    ]);
                }
            } else {
                $booking->forceDelete();
            }
        });
    }
}
