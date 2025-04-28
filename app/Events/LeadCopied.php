<?php

namespace App\Events;

use App\Enum\OperationsEnum;
use App\Models\Lead;
use App\Models\User;
use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Foundation\Events\Dispatchable;


class LeadCopied
{
    use Dispatchable,  SerializesModels;


    public $user;
    public $lead;

    /**
     * Create a new event instance.
     *
     * @param User $user
     * @param Lead $lead
     * @return void
     */
    public function __construct(User $user, Lead $lead)
    {
        $this->user = $user;
        $this->lead = $lead;
    }
    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    // public function broadcastOn(): array
    // {
    //     return [
    //         new Channel('leads'),
    //     ];
    // }
    // public function broadcastAs()
    // {
    //     return 'lead-' . OperationsEnum::COPY;
    // }
}
