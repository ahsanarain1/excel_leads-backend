<?php

namespace App\Events;

use App\Models\Lead;
use App\Enum\OperationsEnum;
use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class LeadsEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $lead;
    public $action;

    /**
     * Create a new event instance.
     *
     * @param  Lead  $lead
     * @param  string  $action
     * @return void
     */
    public function __construct(Lead $lead, $action)
    {
        $this->lead = $lead;
        $this->action = $action;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
        // Check if the action is 'OperationsEnum::STORE' before broadcasting
        if ($this->action === OperationsEnum::STORE) {
            return new Channel('leads');
        }
        return [];
    }
    public function broadcastAs()
    {
        if ($this->action === OperationsEnum::STORE) {
            return 'lead-' . OperationsEnum::STORE;
        }
        return '';
    }
}
