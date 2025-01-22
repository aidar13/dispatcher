<?php

namespace App\Module\Courier\Providers;

use App\Module\Courier\Events\CloseCourierDayCreatedEvent;
use App\Module\Courier\Events\CourierUpdatedEvent;
use App\Module\Courier\Listeners\CourierUpdatedListener;
use App\Module\Courier\Listeners\Integration\IntegrationCloseCourierDayCreatedListener;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        CloseCourierDayCreatedEvent::class => [
            IntegrationCloseCourierDayCreatedListener::class
        ],
        CourierUpdatedEvent::class => [
            CourierUpdatedListener::class
        ],
    ];
}
