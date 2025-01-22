<?php

declare(strict_types=1);

namespace App\Providers;

use App\Module\Car\Listeners\Integration\IntegrationCarCreatedListener;
use App\Module\Car\Listeners\Integration\IntegrationCarOccupancyCreatedListener;
use App\Module\Car\Listeners\Integration\IntegrationCarUpdatedListener;
use App\Module\City\Listeners\Integration\IntegrationCityCreatedListener;
use App\Module\City\Listeners\Integration\IntegrationCityUpdatedListener;
use App\Module\City\Listeners\Integration\IntegrationCountryCreatedListener;
use App\Module\City\Listeners\Integration\IntegrationRegionCreatedListener;
use App\Module\Company\Listeners\CreateCompanyListener;
use App\Module\Company\Listeners\UpdateCompanyListener;
use App\Module\Courier\Listeners\Integration\IntegrationCourierCreatedListener;
use App\Module\Courier\Listeners\Integration\IntegrationCourierPaymentCreatedListener;
use App\Module\Courier\Listeners\Integration\IntegrationCourierStopCreatedListener;
use App\Module\Courier\Listeners\Integration\IntegrationCourierUpdatedListener;
use App\Module\Delivery\Listeners\IntegrationDeliveryCreatedListener;
use App\Module\Delivery\Listeners\IntegrationDeliveryUpdatedListener;
use App\Module\Delivery\Listeners\IntegrationSetDeliveryWaitListListener;
use App\Module\Delivery\Models\Delivery;
use App\Module\File\Listeners\Integration\IntegrationFileCreatedListener;
use App\Module\History\Observers\DeliveryObserver;
use App\Module\History\Observers\OrderTakeObserver;
use App\Module\Order\Listeners\IntegrationAdditionalServiceValueCreatedListener;
use App\Module\Order\Listeners\IntegrationAdditionalServiceValueDeletedListener;
use App\Module\Order\Listeners\IntegrationAdditionalServiceValueUpdatedListener;
use App\Module\Order\Listeners\IntegrationInvoiceCreatedListener;
use App\Module\Order\Listeners\IntegrationInvoiceUpdatedListener;
use App\Module\Order\Listeners\IntegrationOrderCreatedListener;
use App\Module\Order\Listeners\IntegrationOrderUpdatedListener;
use App\Module\Order\Listeners\IntegrationReceiverCreatedListener;
use App\Module\Order\Listeners\IntegrationReceiverUpdatedListener;
use App\Module\Order\Listeners\IntegrationSenderCreatedListener;
use App\Module\Order\Listeners\IntegrationSenderUpdatedListener;
use App\Module\Order\Listeners\IntegrationSlaCreatedListener;
use App\Module\Order\Listeners\IntegrationSlaUpdatedListener;
use App\Module\RabbitMQ\Models\RabbitMQRequest;
use App\Module\Status\Listeners\Integration\IntegrationOrderStatusCreatedListener;
use App\Module\Status\Listeners\Integration\IntegrationWaitListStatusCreatedListener;
use App\Module\Take\Listeners\Integration\IntegrationOrderTakeCreatedListener;
use App\Module\Take\Listeners\Integration\IntegrationOrderTakeUpdatedListener;
use App\Module\Take\Listeners\Integration\IntegrationSetTakeWaitListListener;
use App\Module\Take\Models\OrderTake;
use App\Observers\RabbitMQRequestObserver;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        Registered::class                                 => [
            SendEmailVerificationNotification::class,
        ],
        'cabinet.company.created'                         => [
            CreateCompanyListener::class
        ],
        'cabinet.company.updated'                         => [
            UpdateCompanyListener::class
        ],
        'location.city.created'                           => [
            IntegrationCityCreatedListener::class
        ],
        'location.city.updated'                           => [
            IntegrationCityUpdatedListener::class
        ],
        'location.region.created'                         => [
            IntegrationRegionCreatedListener::class
        ],
        'cabinet.country.created'                         => [
            IntegrationCountryCreatedListener::class,
        ],
        'cabinet.courier-app.take-info.created'           => [
            IntegrationOrderTakeCreatedListener::class,
        ],
        'cabinet.courier-app.take-info.updated'           => [
            IntegrationOrderTakeUpdatedListener::class,
        ],
        'cabinet.courier-app.delivery-info.created'       => [
            IntegrationDeliveryCreatedListener::class,
        ],
        'cabinet.courier-app.delivery-info.updated'       => [
            IntegrationDeliveryUpdatedListener::class,
        ],
        'cabinet.courier-app.courier.created'             => [
            IntegrationCourierCreatedListener::class,
        ],
        'cabinet.courier.updated'                         => [
            IntegrationCourierUpdatedListener::class,
        ],
        'cabinet.courier-app.courier-stop.created'        => [
            IntegrationCourierStopCreatedListener::class,
        ],
        'cabinet.car.created'                             => [
            IntegrationCarCreatedListener::class
        ],
        'cabinet.car.updated'                             => [
            IntegrationCarUpdatedListener::class
        ],
        'cabinet.additional-service-value.created'        => [
            IntegrationAdditionalServiceValueCreatedListener::class
        ],
        'cabinet.additional-service-value.updated'        => [
            IntegrationAdditionalServiceValueUpdatedListener::class
        ],
        'cabinet.order.created'                           => [
            IntegrationOrderCreatedListener::class,
        ],
        'cabinet.order.updated'                           => [
            IntegrationOrderUpdatedListener::class,
        ],
        'cabinet.invoice.created'                         => [
            IntegrationInvoiceCreatedListener::class,
        ],
        'cabinet.invoice.updated'                         => [
            IntegrationInvoiceUpdatedListener::class,
        ],
        'cabinet.sender.created'                          => [
            IntegrationSenderCreatedListener::class,
        ],
        'cabinet.sender.updated'                          => [
            IntegrationSenderUpdatedListener::class,
        ],
        'cabinet.receiver.created'                        => [
            IntegrationReceiverCreatedListener::class,
        ],
        'cabinet.receiver.updated'                        => [
            IntegrationReceiverUpdatedListener::class,
        ],
        'cabinet.order-status.created'                    => [
            IntegrationOrderStatusCreatedListener::class,
        ],
        'cabinet.wait-list-status.created'                => [
            IntegrationWaitListStatusCreatedListener::class,
        ],
        'location.sla.created'                            => [
            IntegrationSlaCreatedListener::class,
        ],
        'location.sla.updated'                            => [
            IntegrationSlaUpdatedListener::class,
        ],
        'cabinet.file.created'                            => [
            IntegrationFileCreatedListener::class
        ],
        'cabinet.courier-app.delivery-info.wait-list.set' => [
            IntegrationSetDeliveryWaitListListener::class
        ],
        'cabinet.courier-app.take-info.wait-list.set'     => [
            IntegrationSetTakeWaitListListener::class
        ],
        'cabinet.car.occupancy.created'                   => [
            IntegrationCarOccupancyCreatedListener::class
        ],
        'cabinet.courier.payment.created'                 => [
            IntegrationCourierPaymentCreatedListener::class
        ],
        'cabinet.additional-service-value.deleted'        => [
            IntegrationAdditionalServiceValueDeletedListener::class
        ]
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot(): void
    {
        RabbitMQRequest::observe(new RabbitMQRequestObserver());
        Delivery::observe(new DeliveryObserver());
        OrderTake::observe(new OrderTakeObserver());
    }
}
