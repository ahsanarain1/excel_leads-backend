<?php

namespace App\Listeners;

use App\Enum\ActivityType;
use App\Events\UserLoginAttempt;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class ListenUserLoginAttempt implements ShouldQueue
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
    public function handle(UserLoginAttempt $event): void
    {
        $user = $event->user;
        // Log the user registration activity
        $user->logActivity(request()->ip(), ActivityType::TWOFACTOR);
    }
}
