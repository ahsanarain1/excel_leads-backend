<?php

namespace App\Listeners;

use App\Enum\ActivityType;
use App\Events\UserLoggedIn;

use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class ListenUserLoggedIn implements ShouldQueue
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
     *
     * @param  \Illuminate\Auth\Events\Login  $event
     * @return void
     */
    public function handle(UserLoggedIn $event): void
    {
        /** @var \App\Models\User $user **/
        $user = $event->user;
        // Log the user login activity
        $user->logActivity(request()->ip(), ActivityType::LOGIN);
    }
}
