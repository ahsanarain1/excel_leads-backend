<?php

namespace App\Providers;

use App\Events\UserLoggedIn;
use App\Events\UserRegistered;

use App\Events\FailedLoginAttempt;
use App\Events\LeadCopied;
use App\Events\LeadDeleted;
use App\Listeners\ListenUserLoggedIn;
use Illuminate\Support\Facades\Event;

use Illuminate\Auth\Events\Registered;
use App\Listeners\ListenUserRegistered;
use App\Listeners\LogFailedLoginAttempt;

use App\Listeners\LogLeadCopy;
use App\Listeners\LogLeadDelete;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event to listener mappings for the application.
     *
     * @var array<class-string, array<int, class-string>>
     */
    protected $listen = [
        UserRegistered::class => [
            ListenUserRegistered::class,
        ],
        UserLoggedIn::class => [
            ListenUserLoggedIn::class,
        ],
        FailedLoginAttempt::class => [
            LogFailedLoginAttempt::class,
        ],
        LeadCopied::class => [
            LogLeadCopy::class,
        ],
        LeadDeleted::class => [
            LogLeadDelete::class,
        ],
    ];

    /**
     * Register any events for your application.
     */
    public function boot(): void
    {
        //
    }

    /**
     * Determine if events and listeners should be automatically discovered.
     */
    public function shouldDiscoverEvents(): bool
    {
        return false;
    }
}
