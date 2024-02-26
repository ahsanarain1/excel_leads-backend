<?php

namespace App\Listeners;

use App\Enum\ActivityType;
use App\Events\UserLoginAttempt;
use App\Notifications\SendTwoFactorCode;
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

        // Generate a new verification code and send the verification email
        $verificationCode = $user->generateVerificationCode();
        $user->notify(new SendTwoFactorCode($verificationCode));

        // Log the user registration activity
        $user->logActivity(request()->ip(), ActivityType::TWOFACTOR);
    }
}
