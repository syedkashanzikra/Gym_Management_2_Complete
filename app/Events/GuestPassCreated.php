<?php

namespace App\Events;

use App\Models\GuestPass;
use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class GuestPassCreated
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $guestPass;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(GuestPass $guestPass)
    {
        $this->guestPass = $guestPass->load('user');
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel('channel-name');
    }
}
