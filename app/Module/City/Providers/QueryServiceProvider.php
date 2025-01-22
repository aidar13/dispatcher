<?php

declare(strict_types=1);

namespace App\Module\City\Providers;

use App\Module\City\Contracts\Queries\CityQuery as CityQueryContract;
use App\Module\City\Contracts\Queries\CountryQuery as CountryQueryContract;
use App\Module\City\Contracts\Queries\RegionQuery as RegionQueryContract;
use App\Module\City\Queries\CityQuery;
use App\Module\City\Queries\CountryQuery;
use App\Module\City\Queries\RegionQuery;
use Illuminate\Support\ServiceProvider;

final class QueryServiceProvider extends ServiceProvider
{
    public array $bindings = [
        CityQueryContract::class    => CityQuery::class,
        RegionQueryContract::class  => RegionQuery::class,
        CountryQueryContract::class => CountryQuery::class,
    ];
}
