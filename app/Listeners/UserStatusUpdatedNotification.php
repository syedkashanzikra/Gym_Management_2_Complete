<?php

namespace App\Listeners;

use Coderstm\Enum\AppStatus;
use App\Events\UserStatusUpdated;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Notifications\ActiveMemberNotification;
use App\Notifications\DeactiveMemberNotification;

class UserStatusUpdatedNotification implements ShouldQueue
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
     * @param  \App\Events\UserStatusUpdated  $event
     * @return void
     */
    public function handle(UserStatusUpdated $event)
    {
        $user = $event->log->logable;
        $current = $event->log->options['status']['current'];
        if ($current === AppStatus::DEACTIVE->value) {
            $this->cancelNow($user);
            admin_notify(new DeactiveMemberNotification($event->log, $current));
        } else if ($current === AppStatus::ACTIVE->value) {
            admin_notify(new ActiveMemberNotification($event->log));
        } else if ($current === AppStatus::HOLD->value) {
            $this->cancelNow($user);
        }
    }

    protected function cancelNow($user)
    {
        try {
            $user->subscription()->cancelNow();
        } catch (\Throwable $th) {
            //throw $th;
        }
    }
}
