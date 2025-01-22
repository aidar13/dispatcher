<?php

declare(strict_types=1);

namespace App\Module\Delivery\Providers;

use App\Module\CourierApp\Handlers\Delivery\IntegrationOneC\ChangeDeliveryStatusInOneCHandler;
use App\Module\Delivery\Contracts\Repositories\CreateDeliveryRepository;
use App\Module\Delivery\Contracts\Repositories\CreateReturnDeliveryRepository;
use App\Module\Delivery\Contracts\Repositories\CreateRouteSheetInvoiceRepository;
use App\Module\Delivery\Contracts\Repositories\CreateRouteSheetRepository;
use App\Module\Delivery\Contracts\Repositories\DeleteReturnDeliveryRepository;
use App\Module\Delivery\Contracts\Repositories\Integration\CreateDeliveriesInCabinetRepository;
use App\Module\Delivery\Contracts\Repositories\UpdateDeliveryRepository;
use App\Module\Delivery\Contracts\Repositories\UpdateRouteSheetRepository;
use App\Module\Delivery\Repositories\Eloquent\DeliveryRepository;
use App\Module\Delivery\Repositories\Eloquent\ReturnDeliveryRepository;
use App\Module\Delivery\Repositories\Eloquent\RouteSheetInvoiceRepository;
use App\Module\Delivery\Repositories\Eloquent\RouteSheetRepository;
use App\Module\Delivery\Repositories\OneC\DeliveryOneCRepository;
use Illuminate\Support\ServiceProvider;
use App\Module\Delivery\Repositories\Http\CreateDeliveriesInCabinetRepository as CreateDeliveriesInCabinetRepositoryContract;

final class RepositoryServiceProvider extends ServiceProvider
{
    public array $bindings = [
        CreateDeliveryRepository::class            => DeliveryRepository::class,
        UpdateDeliveryRepository::class            => DeliveryRepository::class,
        CreateReturnDeliveryRepository::class      => ReturnDeliveryRepository::class,
        DeleteReturnDeliveryRepository::class      => ReturnDeliveryRepository::class,

        //Route Sheet
        CreateRouteSheetRepository::class          => RouteSheetRepository::class,
        CreateRouteSheetInvoiceRepository::class   => RouteSheetInvoiceRepository::class,
        UpdateRouteSheetRepository::class          => RouteSheetRepository::class,
        CreateDeliveriesInCabinetRepository::class => CreateDeliveriesInCabinetRepositoryContract::class
    ];

    public function register(): void
    {
        $this->app->when(ChangeDeliveryStatusInOneCHandler::class)
            ->needs(UpdateDeliveryRepository::class)
            ->give(DeliveryOneCRepository::class);
    }
}
