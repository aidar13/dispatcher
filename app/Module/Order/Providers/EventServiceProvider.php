<?php

namespace App\Module\Order\Providers;

use App\Module\Order\Events\InvoiceCreatedEvent;
use App\Module\Order\Events\InvoiceSectorsUpdatedEvent;
use App\Module\Order\Events\ReceiverUpdatedEvent;
use App\Module\Order\Events\SenderCreatedEvent;
use App\Module\Order\Events\SenderUpdatedEvent;
use App\Module\Order\Listeners\Integration\InvoiceSectorsUpdatedListener;
use App\Module\Order\Listeners\SetDeliveryCustomerSectorListener;
use App\Module\Order\Listeners\SetInvoiceSectorsListener;
use App\Module\Order\Listeners\SetInvoiceSlaDateListener;
use App\Module\Order\Listeners\SetTakeCustomerSectorListener;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        InvoiceCreatedEvent::class        => [
            SetInvoiceSlaDateListener::class,
            SetInvoiceSectorsListener::class,
        ],
        SenderCreatedEvent::class         => [
            SetTakeCustomerSectorListener::class,
        ],
        SenderUpdatedEvent::class         => [
            SetTakeCustomerSectorListener::class,
        ],
        ReceiverUpdatedEvent::class         => [
            SetDeliveryCustomerSectorListener::class,
        ],
        InvoiceSectorsUpdatedEvent::class => [
            InvoiceSectorsUpdatedListener::class,
        ],
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
