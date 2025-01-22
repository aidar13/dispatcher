<?php

declare(strict_types=1);

namespace App\Module\Delivery\Providers;

use App\Module\Delivery\Contracts\Queries\DeliveryQuery as DeliveryQueryContract;
use App\Module\Delivery\Contracts\Queries\PredictionQuery as PredictionQueryContract;
use App\Module\Delivery\Contracts\Queries\RouteSheetQuery as RouteSheetQueryContract;
use App\Module\Delivery\Contracts\Queries\ReturnDeliveryQuery as ReturnDeliveryQueryContract;
use App\Module\Delivery\Queries\ReturnDeliveryQuery;
use App\Module\Delivery\Queries\RouteSheetQuery;
use App\Module\Delivery\Queries\DeliveryQuery;
use App\Module\Delivery\Queries\PredictionQuery;
use Illuminate\Support\ServiceProvider;

final class QueryServiceProvider extends ServiceProvider
{
    public array $bindings = [
        DeliveryQueryContract::class       => DeliveryQuery::class,
        PredictionQueryContract::class     => PredictionQuery::class,
        ReturnDeliveryQueryContract::class => ReturnDeliveryQuery::class,

        //RouteSheet
        RouteSheetQueryContract::class     => RouteSheetQuery::class
    ];
}
