<?php

namespace App\Listeners;

use App\Enum\ActivityType;
use App\Events\LeadDeleted;
use App\Models\UserActivity;

class LogLeadDelete
{
    public function handle(LeadDeleted $event): void
    {
        UserActivity::create([
            'user_id' => $event->user->id,
            'lead_id' => $event->lead->id,
            'activity_type' => ActivityType::LEAD_DELETE,
            'ip_address' => request()->ip(),
        ]);
    }
}
