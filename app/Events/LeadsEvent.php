<?php

namespace App\Events;

use App\Enum\ActivityType;
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
        // Only broadcast for specific activity types
        $broadcastableActions = [
            ActivityType::LEAD_DELETE,
            ActivityType::LEAD_COPY,
        ];

        return in_array($this->action, $broadcastableActions, true)
            ? [new Channel('leads')]
            : [];
    }

    public function broadcastAs(): string
    {
        return match ($this->action) {
            ActivityType::LEAD_DELETE => 'lead-' . OperationsEnum::DELETE,
            ActivityType::LEAD_COPY => 'lead-' . OperationsEnum::COPY,
            default => '',
        };
    }
}
