<?php

namespace App\Listeners\Usages;

use App\Events\BookingCreated;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class AddClassesFeatureUsages
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
     * @param  \App\Events\BookingCreated  $event
     * @return void
     */
    public function handle(BookingCreated $event)
    {
        $user = $event->booking->user;
        if ($user && !$event->standby && $user->subscription()) {
            $user->subscription()->recordFeatureUsage('classes');
        }
    }
}
