<?php

declare(strict_types=1);

namespace App\Module\Routing\Providers;

use App\Module\Routing\Contracts\Queries\RoutingQuery as RoutingQueryContract;
use App\Module\Routing\Queries\RoutingQuery;
use Illuminate\Support\ServiceProvider;

final class QueryServiceProvider extends ServiceProvider
{
    public array $bindings = [
        RoutingQueryContract::class => RoutingQuery::class
    ];
}
