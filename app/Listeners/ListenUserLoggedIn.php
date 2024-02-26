<?php

namespace App\Listeners;

use App\Enum\ActivityType;
use App\Events\UserLoggedIn;
use App\Notifications\SendTwoFactorCode;
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

        // Generate a new verification code and store it
        $verificationCode = $user->generateVerificationCode();

        // Send the verification email with the stored verification code
        $user->notify(new SendTwoFactorCode($verificationCode));

        // Log the user login activity
        $user->logActivity(request()->ip(), ActivityType::LOGIN);
    }
}
