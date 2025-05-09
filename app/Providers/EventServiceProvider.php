<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use App\Events\VideoCreated;
use App\Listeners\SendVideoCreatedNotification;

class EventServiceProvider extends ServiceProvider
{
    /** The event to listener mappings for the application.
     * @var array<class-string, array<int, class-string>> */
    protected $listen = [
        VideoCreated::class => [
            SendVideoCreatedNotification::class,
        ],
    ];

    /** Register any events for your application. */
    public function boot(): void
    {
        parent::boot();
    }
}
