<?php

namespace App\Listeners\Usages;

use App\Events\GuestPassCreated;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class AddGuestPassFeatureUsages
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
     * @param  \App\Events\GuestPassCreated  $event
     * @return void
     */
    public function handle(GuestPassCreated $event)
    {
        $user = $event->guestPass->user;
        if ($user && $user->subscription()) {
            $user->subscription()->recordFeatureUsage('guest-pass');
        }
    }
}
