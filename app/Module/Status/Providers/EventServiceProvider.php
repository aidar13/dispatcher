<?php

declare(strict_types=1);

namespace App\Module\Status\Providers;

use App\Module\Delivery\Listeners\UpdateDeliveryStatusListener;
use App\Module\Order\Listeners\UpdateInvoiceCargoTypeListener;
use App\Module\Status\Events\OrderStatusCreatedEvent;
use App\Module\Status\Events\WaitListStatusCreatedEvent;
use App\Module\Status\Listeners\CloseDeliveryFromProviderListener;
use App\Module\Status\Listeners\CourierReturnDeliveryListener;
use App\Module\Status\Listeners\SendWaitListConfirmedPushNotificationListener;
use App\Module\Status\Listeners\SendWaitListDeniedPushNotificationListener;
use App\Module\Status\Listeners\SetCancelStatusToDeliveryListener;
use App\Module\Status\Listeners\SetDeliveredStatusToDeliveryListener;
use App\Module\Status\Listeners\SetInvoiceWaitListIdListener;
use App\Module\Status\Listeners\SetInvoiceWaveListener;
use App\Module\Take\Listeners\UpdateTakeStatusListener;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        OrderStatusCreatedEvent::class => [
            UpdateInvoiceCargoTypeListener::class,
            SetInvoiceWaveListener::class,
            UpdateTakeStatusListener::class,
            UpdateDeliveryStatusListener::class,
            SetCancelStatusToDeliveryListener::class,
            SetDeliveredStatusToDeliveryListener::class,
            CourierReturnDeliveryListener::class,
            SetInvoiceWaitListIdListener::class,
            CloseDeliveryFromProviderListener::class,
        ],

        WaitListStatusCreatedEvent::class => [
            SendWaitListConfirmedPushNotificationListener::class,
            SendWaitListDeniedPushNotificationListener::class,
        ]
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot(): void
    {
        parent::boot();
    }
}
