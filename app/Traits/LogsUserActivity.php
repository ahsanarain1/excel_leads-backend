<?php

namespace App\Traits;

use App\Models\UserActivity;

trait LogsUserActivity
{
    public function logActivity($ipAddress, $activityType)
    {
        UserActivity::create([
            'user_id' => $this->id,
            'ip_address' => $ipAddress,
            'activity_type' => $activityType,
        ]);
    }

    public function userActivity()
    {
        return $this->hasMany(UserActivity::class);
    }
}
