<?php

namespace App\Listeners;

use App\Events\BookingCreated;
use App\Events\BookingCanceled;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Notification;
use App\Notifications\BookingCanceledNotification;

class ProcessStandbyBooking implements ShouldQueue
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  \App\Events\BookingCanceled  $event
     * @return void
     */
    public function handle(BookingCanceled $event)
    {
        $booking = $event->booking;
        if (!$booking->is_stand_by) {
            $schedule = $booking->schedule;
            $schedule->updateStandbyBookings();
        }
    }
}
