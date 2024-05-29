<?php

namespace App\Listeners;

use App\Enum\ActivityType;
use App\Events\LeadCopied;
use App\Models\UserActivity;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class LogLeadCopy
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(LeadCopied $event): void
    {
        UserActivity::create([
            'user_id' => $event->user->id,
            'activity_type' => ActivityType::LEAD_COPY,
            'lead_id' => $event->lead->id,
            'ip_address' => request()->ip(),
        ]);
    }
}
