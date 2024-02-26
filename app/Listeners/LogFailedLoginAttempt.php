<?php

namespace App\Listeners;



use App\Events\FailedLoginAttempt;
use App\Models\FailedLoginAttempt as FailedLoginAttemptModel;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class LogFailedLoginAttempt
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
     * @param  \App\Events\FailedLoginAttempt  $event
     * @return void
     */
    public function handle(FailedLoginAttempt $event): void
    {
        // Log the failed login attempt in the database
        FailedLoginAttemptModel::create([
            'email' => $event->email,
            'ip_address' => $event->ipAddress,
            'attempted_at' => now(),
        ]);
    }
}
