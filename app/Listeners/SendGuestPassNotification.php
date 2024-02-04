<?php

namespace App\Listeners;

use App\Events\GuestPassCreated;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Notifications\GuestPassNotification;
use Illuminate\Support\Facades\Notification;

class SendGuestPassNotification implements ShouldQueue
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
        admin_notify(new GuestPassNotification($event->guestPass));
    }
}
