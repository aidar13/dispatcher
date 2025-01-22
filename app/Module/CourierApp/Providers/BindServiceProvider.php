<?php

declare(strict_types=1);

namespace App\Module\CourierApp\Providers;

use App\Module\CourierApp\Contracts\Services\Delivery\CourierDeliveryService as CourierDeliveryServiceContract;
use App\Module\CourierApp\Contracts\Services\OrderTake\CourierOrderTakeService as CourierOrderTakeServiceContract;
use App\Module\CourierApp\Services\Delivery\CourierDeliveryService;
use App\Module\CourierApp\Services\OrderTake\CourierOrderTakeService;
use Illuminate\Support\ServiceProvider;

final class BindServiceProvider extends ServiceProvider
{
    public array $bindings = [
        CourierOrderTakeServiceContract::class => CourierOrderTakeService::class,
        CourierDeliveryServiceContract::class  => CourierDeliveryService::class,
    ];
}
