<?php

declare(strict_types=1);

namespace App\Module\Planning\Providers;

use App\Module\Planning\Events\ContainersSentToAssemblyEvent;
use App\Module\Planning\Events\ContainerStatusUpdatedEvent;
use App\Module\Planning\Events\FastDeliveryOrderCreatedByContainerEvent;
use App\Module\Planning\Events\PartiallyAssembledInvoicesNotificationEvent;
use App\Module\Planning\Listeners\ContainersSentToAssemblyListener;
use App\Module\Planning\Listeners\CreateFastDeliveryOrderListener;
use App\Module\Planning\Listeners\CreateFastDeliveryOrdersByContainerListener;
use App\Module\Planning\Listeners\PartiallyAssembledSendEmailListener;
use App\Module\Planning\Listeners\PartiallyAssembledSendNotificationListener;
use App\Module\Planning\Listeners\SetInternalIdByContainerToFastDeliveryOrderListener;
use App\Module\Planning\Listeners\UpdateContainerInvoiceStatusesListener;
use App\Module\Planning\Listeners\UpdateInvoicePlaceQuantityListener;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

final class EventServiceProviders extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        ContainerStatusUpdatedEvent::class                 => [
            UpdateContainerInvoiceStatusesListener::class,
            UpdateInvoicePlaceQuantityListener::class,
            CreateFastDeliveryOrderListener::class,
        ],
        PartiallyAssembledInvoicesNotificationEvent::class => [
            PartiallyAssembledSendEmailListener::class,
            PartiallyAssembledSendNotificationListener::class,
        ],
        ContainersSentToAssemblyEvent::class               => [
            ContainersSentToAssemblyListener::class,
            CreateFastDeliveryOrdersByContainerListener::class
        ],
        FastDeliveryOrderCreatedByContainerEvent::class    => [
            SetInternalIdByContainerToFastDeliveryOrderListener::class
        ]
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
    }
}
