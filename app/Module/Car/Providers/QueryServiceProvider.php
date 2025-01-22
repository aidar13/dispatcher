<?php

declare(strict_types=1);

namespace App\Module\Car\Providers;

use App\Module\Car\Contracts\Queries\CarOccupancyTypeQuery as CarOccupancyTypeQueryContract;
use App\Module\Car\Contracts\Queries\CarOccupancyQuery as CarOccupancyQueryContract;
use App\Module\Car\Contracts\Queries\CarQuery as CarQueryContract;
use App\Module\Car\Queries\CarOccupancyQuery;
use App\Module\Car\Queries\CarOccupancyTypeQuery;
use App\Module\Car\Queries\CarQuery;
use Illuminate\Support\ServiceProvider;

final class QueryServiceProvider extends ServiceProvider
{
    public array $bindings = [
        CarQueryContract::class              => CarQuery::class,
        CarOccupancyTypeQueryContract::class => CarOccupancyTypeQuery::class,
        CarOccupancyQueryContract::class     => CarOccupancyQuery::class,
    ];
}
