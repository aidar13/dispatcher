<?php

declare(strict_types=1);

namespace App\Module\Delivery\Providers;

use App\Module\Delivery\Contracts\Services\DeliveryService as DeliveryContract;
use App\Module\Delivery\Contracts\Services\PredictionService as PredictionServiceContract;
use App\Module\Delivery\Contracts\Services\RouteSheetService as RouteSheetServiceContract;
use App\Module\Delivery\Services\DeliveryService;
use App\Module\Delivery\Services\PredictionService;
use App\Module\Delivery\Services\RouteSheetService;
use Illuminate\Support\ServiceProvider;

class BindServiceProvider extends ServiceProvider
{
    public array $bindings = [
        DeliveryContract::class          => DeliveryService::class,
        PredictionServiceContract::class => PredictionService::class,
        RouteSheetServiceContract::class => RouteSheetService::class,
    ];
}
