<?php

declare(strict_types=1);

namespace App\Module\Take\Providers;

use App\Module\Order\Listeners\ChangeInvoiceTakeDateListener;
use App\Module\Order\Listeners\Integration\SendTakeDateChangedByOrderToCabinetListener;
use App\Module\Take\Events\ChangedTakeDateByOrderEvent;
use App\Module\Take\Events\OrderTakeAssignedToCourierEvent;
use App\Module\Take\Events\OrderTakeDateChangedEvent;
use App\Module\Take\Events\OrderTakesAssignedToCourierEvent;
use App\Module\Take\Events\OrderTakeStatusUpdatedEvent;
use App\Module\Take\Listeners\CourierAssignToOrderTakeRoutingListener;
use App\Module\Take\Listeners\Integration\CourierAssignToOrderTakeCabinetListener;
use App\Module\Take\Listeners\Integration\CourierAssignToOrderTakeNotificationListener;
use App\Module\Take\Listeners\Integration\IntegrationOrderStatusCreateListener;
use App\Module\Take\Listeners\Integration\SetChangedTakeDateWaitListStatusRepositoryListener;
use App\Module\Take\Listeners\SendPushNotificationOrderTakeCanceledListener;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        OrderTakeAssignedToCourierEvent::class  => [
            IntegrationOrderStatusCreateListener::class,
        ],
        OrderTakesAssignedToCourierEvent::class => [
            CourierAssignToOrderTakeCabinetListener::class,
            CourierAssignToOrderTakeNotificationListener::class,
            CourierAssignToOrderTakeRoutingListener::class,
        ],
        OrderTakeDateChangedEvent::class        => [
            ChangeInvoiceTakeDateListener::class,
        ],
        ChangedTakeDateByOrderEvent::class      => [
            SetChangedTakeDateWaitListStatusRepositoryListener::class,
            SendTakeDateChangedByOrderToCabinetListener::class
        ],
        OrderTakeStatusUpdatedEvent::class     => [
            SendPushNotificationOrderTakeCanceledListener::class,
        ]
    ];
}
