<?php

declare(strict_types=1);

namespace App\Module\CourierApp\Providers;

use App\Module\CourierApp\Events\Delivery\DeliveryInfoWaitListStatusChangedEvent;
use App\Module\CourierApp\Events\Delivery\DeliveryStatusChangedEvent;
use App\Module\CourierApp\Events\OrderTake\CourierShortcomingFilesSavedEvent;
use App\Module\CourierApp\Events\OrderTake\InvoiceCargoSizeTypeSetEvent;
use App\Module\CourierApp\Events\OrderTake\OrderTakeInfoWaitListStatusChangedInfoEvent;
use App\Module\CourierApp\Events\OrderTake\OrderTakeStatusChangedEvent;
use App\Module\CourierApp\Events\OrderTake\TakeWaitListStatusChangedEvent;
use App\Module\CourierApp\Listeners\Delivery\CreateDeliveryCourierStopListener;
use App\Module\CourierApp\Listeners\Delivery\DeliveryStatusChangedListener;
use App\Module\CourierApp\Listeners\Delivery\SendDeliveryInfoWaitListNotificationListener;
use App\Module\CourierApp\Listeners\Integration\ChangeDeliveryWaitListStatusOneCListener;
use App\Module\CourierApp\Listeners\Integration\ChangeTakeWaitListStatusOneCListener;
use App\Module\CourierApp\Listeners\Integration\CreateMindSaleDealListener;
use App\Module\CourierApp\Listeners\Integration\CreateOrderWaitListStatusByDeliveryListener;
use App\Module\CourierApp\Listeners\Integration\CreateOrderWaitListStatusByTakeListener;
use App\Module\CourierApp\Listeners\Integration\CreateWriteOffForSparkDeliveryListener;
use App\Module\CourierApp\Listeners\Integration\SendInvoiceCargoSizeTypeInSparkDeliveryListener;
use App\Module\CourierApp\Listeners\OrderTake\CreateOrderTakeCourierStopListener;
use App\Module\CourierApp\Listeners\OrderTake\CreateShortcomingWaitListStatusListener;
use App\Module\CourierApp\Listeners\OrderTake\OrderTakeStatusChangedListener;
use App\Module\CourierApp\Listeners\OrderTake\SendEmailShortcomingFilesSavedListener;
use App\Module\CourierApp\Listeners\OrderTake\SendTakeInfoWaitListNotificationListener;
use App\Module\Take\Listeners\Integration\IntegrationOrderStatusCreateListener;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

final class EventServiceProvider extends ServiceProvider
{
    protected $listen = [
        OrderTakeStatusChangedEvent::class => [
            IntegrationOrderStatusCreateListener::class,
            OrderTakeStatusChangedListener::class,
            CreateOrderTakeCourierStopListener::class,
        ],

        CourierShortcomingFilesSavedEvent::class => [
            SendEmailShortcomingFilesSavedListener::class,
            CreateShortcomingWaitListStatusListener::class,
        ],

        DeliveryStatusChangedEvent::class => [
            IntegrationOrderStatusCreateListener::class,
            CreateDeliveryCourierStopListener::class,
            DeliveryStatusChangedListener::class,
        ],

        DeliveryInfoWaitListStatusChangedEvent::class => [
            CreateOrderWaitListStatusByDeliveryListener::class,
            ChangeDeliveryWaitListStatusOneCListener::class,
            SendDeliveryInfoWaitListNotificationListener::class,
            CreateMindSaleDealListener::class,
        ],

        TakeWaitListStatusChangedEvent::class => [
            CreateOrderWaitListStatusByTakeListener::class,
            ChangeTakeWaitListStatusOneCListener::class,
            SendTakeInfoWaitListNotificationListener::class,
        ],

        OrderTakeInfoWaitListStatusChangedInfoEvent::class => [
            CreateMindSaleDealListener::class,
        ],

        InvoiceCargoSizeTypeSetEvent::class => [
            SendInvoiceCargoSizeTypeInSparkDeliveryListener::class,
            CreateWriteOffForSparkDeliveryListener::class,
        ],
    ];
}
