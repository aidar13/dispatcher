<?php

namespace App\Module\Delivery\Providers;

use App\Module\Delivery\Events\DeliveryStatusUpdatedEvent;
use App\Module\Delivery\Events\RouteSheetCreatedFromOneCEvent;
use App\Module\Delivery\Listeners\SendPushNotificationDeliveryCanceledListener;
use App\Module\Delivery\Listeners\SendRouteSheetToCabinetListener;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        RouteSheetCreatedFromOneCEvent::class => [
            SendRouteSheetToCabinetListener::class,
        ],
        DeliveryStatusUpdatedEvent::class => [
            SendPushNotificationDeliveryCanceledListener::class,
        ],
    ];
}
