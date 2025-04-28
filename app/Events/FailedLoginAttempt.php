<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class FailedLoginAttempt
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $email;
    public $user;
    public $ipAddress;

    /**
     * Create a new event instance.
     *
     * @param string $email
     * @param string $ipAddress
     */
    public function __construct($user, $ipAddress)
    {
        // $this->email = $email;
        $this->user = $user;
        $this->ipAddress = $ipAddress;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
        return [
            // new PrivateChannel('channel-name'),
        ];
    }
}
