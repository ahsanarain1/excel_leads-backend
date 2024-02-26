<?php

namespace App\Listeners;

use App\Enum\ActivityType;
use App\Events\UserRegistered;
use App\Notifications\SendTwoFactorCode;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class ListenUserRegistered implements ShouldQueue
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
     * @param  \App\Events\UserRegistered  $event
     * @return void
     */
    public function handle(UserRegistered $event): void
    {
        $user = $event->user;

        // Generate a new verification code and send the verification email
        $verificationCode = $user->generateVerificationCode();
        $user->notify(new SendTwoFactorCode($verificationCode));

        // Log the user registration activity
        $user->logActivity(request()->ip(), ActivityType::REGISTERED);
    }
}
