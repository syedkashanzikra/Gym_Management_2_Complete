<?php

namespace App\Listeners\Usages;

use App\Events\BookingCanceled;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class RemoveClassesFeatureUsages
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
        $user = $event->booking->user;
        if ($user && !$event->standby && $user->subscription()) {
            $user->subscription()->reduceFeatureUsage('classes');
        }
    }
}
