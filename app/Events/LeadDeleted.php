<?php

namespace App\Events;

use App\Models\Lead;
use App\Models\User;
use App\Enum\ActivityType;
use App\Enum\OperationsEnum;
use Illuminate\Broadcasting\Channel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class LeadDeleted implements ShouldBroadcast
{
    use Dispatchable, SerializesModels;

    public Lead $lead;
    public User $user;

    public function __construct(User $user, Lead $lead)
    {
        $this->lead = $lead;
        $this->user = $user;
    }

    public function broadcastOn(): array
    {
        return [new Channel('leads')];
    }

    public function broadcastAs(): string
    {
        return 'lead-' . OperationsEnum::DELETE;
    }

    public function broadcastWith(): array
    {
        return [
            'lead_id' => $this->lead->id,
            'lead_name' => $this->lead->name,
            'user_id' => $this->user->id,
            'user_name' => $this->user->name,
            'activity_type' => ActivityType::LEAD_DELETE,
        ];
    }
}
