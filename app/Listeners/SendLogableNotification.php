<?php

namespace App\Listeners;

use App\Models\User;
use Coderstm\Events\LogCreated;
use App\Events\UserStatusUpdated;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendLogableNotification
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
     * @param  \App\Events\Coderstm\Events\LogCreated  $event
     * @return void
     */
    public function handle(LogCreated $event)
    {
        $model = $event->log;
        if ($model->logable instanceof User && isset($model->options['status'])) {
            event(new UserStatusUpdated($model, $model->options['status']['current']));
        }
    }
}
