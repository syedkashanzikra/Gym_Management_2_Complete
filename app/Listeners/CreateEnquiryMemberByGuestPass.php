<?php

namespace App\Listeners;

use App\Models\User;
use Coderstm\Enum\AppStatus;
use App\Events\GuestPassCreated;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Notification;

class CreateEnquiryMemberByGuestPass implements ShouldQueue
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
        $user = User::updateOrCreate([
            'email' => $event->guestPass->email
        ], [
            'title' => $event->guestPass->title,
            'first_name' => $event->guestPass->first_name,
            'last_name' => $event->guestPass->last_name,
            'phone_number' => $event->guestPass->phone_number,
            'note' => $event->guestPass->note,
            'status' => AppStatus::PENDING->value,
        ]);

        if ($user->wasRecentlyCreated) {
            $user->log_options = [
                'ref' => 'Member'
            ];
            event('eloquent.created: App\Models\User', $user);
            $user->logs()->create([
                'message' => "Guest Pass request from <strong>{$event->guestPass->user->name}</strong>.",
                'type' => 'guest_pass',
                'options' => [
                    'guest_pass_id' => $event->guestPass->id
                ]
            ]);
        }
    }
}
