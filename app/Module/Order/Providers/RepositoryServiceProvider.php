<?php

declare(strict_types=1);

namespace App\Module\Order\Providers;

use App\Module\Order\Contracts\Repositories\CreateAdditionalServiceValueRepository;
use App\Module\Order\Contracts\Repositories\CreateFastDeliveryOrderRepository;
use App\Module\Order\Contracts\Repositories\CreateInvoiceCargoRepository;
use App\Module\Order\Contracts\Repositories\CreateInvoiceRepository;
use App\Module\Order\Contracts\Repositories\CreateOrderRepository;
use App\Module\Order\Contracts\Repositories\CreateReceiverRepository;
use App\Module\Order\Contracts\Repositories\CreateSenderRepository;
use App\Module\Order\Contracts\Repositories\CreateSlaRepository;
use App\Module\Order\Contracts\Repositories\DeleteAdditionalServiceValueRepository;
use App\Module\Order\Contracts\Repositories\Integration\ChangeInvoiceTakeDataRepository;
use App\Module\Order\Contracts\Repositories\Integration\CreateFastDeliveryOrderRepository as CreateFastDeliveryOrderRepositoryHttp;
use App\Module\Order\Contracts\Repositories\Integration\UpdateInvoiceSectorsRepository;
use App\Module\Order\Contracts\Repositories\UpdateAdditionalServiceValueRepository;
use App\Module\Order\Contracts\Repositories\UpdateFastDeliveryOrderRepository;
use App\Module\Order\Contracts\Repositories\UpdateInvoiceCargoRepository;
use App\Module\Order\Contracts\Repositories\UpdateInvoiceRepository;
use App\Module\Order\Contracts\Repositories\UpdateOrderRepository;
use App\Module\Order\Contracts\Repositories\UpdateReceiverRepository;
use App\Module\Order\Contracts\Repositories\UpdateSenderRepository;
use App\Module\Order\Contracts\Repositories\UpdateSlaRepository;
use App\Module\Order\Repositories\Eloquent\AdditionalServiceValueRepository;
use App\Module\Order\Repositories\Eloquent\FastDeliveryOrderRepository;
use App\Module\Order\Repositories\Eloquent\InvoiceCargoRepository;
use App\Module\Order\Repositories\Eloquent\InvoiceRepository;
use App\Module\Order\Repositories\Eloquent\OrderRepository;
use App\Module\Order\Repositories\Eloquent\ReceiverRepository;
use App\Module\Order\Repositories\Eloquent\SenderRepository;
use App\Module\Order\Repositories\Eloquent\SlaRepository;
use App\Module\Order\Repositories\Http\ChangeInvoiceTakeDataRepository as IntegrationChangeInvoiceTakeDataRepositoryContract;
use App\Module\Order\Repositories\Http\FastDeliveryOrderRepository as FastDeliveryOrderRepositoryHttp;
use App\Module\Order\Contracts\Repositories\Integration\CancelInvoiceRepository;
use App\Module\Order\Repositories\Http\CancelInvoiceRepository as HttpCancelInvoiceRepository;
use App\Module\Order\Repositories\Http\InvoiceRepository as HttpInvoiceRepository;
use Illuminate\Support\ServiceProvider;

final class RepositoryServiceProvider extends ServiceProvider
{
    public array $bindings = [
        CreateSenderRepository::class       => SenderRepository::class,
        CreateOrderRepository::class        => OrderRepository::class,
        CreateReceiverRepository::class     => ReceiverRepository::class,
        CreateInvoiceRepository::class      => InvoiceRepository::class,
        CreateInvoiceCargoRepository::class => InvoiceCargoRepository::class,
        CreateSlaRepository::class          => SlaRepository::class,
        UpdateSlaRepository::class          => SlaRepository::class,

        UpdateSenderRepository::class       => SenderRepository::class,
        UpdateOrderRepository::class        => OrderRepository::class,
        UpdateReceiverRepository::class     => ReceiverRepository::class,
        UpdateInvoiceRepository::class      => InvoiceRepository::class,
        UpdateInvoiceCargoRepository::class => InvoiceCargoRepository::class,

        CreateFastDeliveryOrderRepository::class => FastDeliveryOrderRepository::class,
        UpdateFastDeliveryOrderRepository::class => FastDeliveryOrderRepository::class,

        CreateAdditionalServiceValueRepository::class => AdditionalServiceValueRepository::class,
        UpdateAdditionalServiceValueRepository::class => AdditionalServiceValueRepository::class,
        DeleteAdditionalServiceValueRepository::class => AdditionalServiceValueRepository::class,

        // Http
        ChangeInvoiceTakeDataRepository::class        => IntegrationChangeInvoiceTakeDataRepositoryContract::class,
        CancelInvoiceRepository::class                => HttpCancelInvoiceRepository::class,
        UpdateInvoiceSectorsRepository::class         => HttpInvoiceRepository::class,
        CreateFastDeliveryOrderRepositoryHttp::class  => FastDeliveryOrderRepositoryHttp::class
    ];
}
