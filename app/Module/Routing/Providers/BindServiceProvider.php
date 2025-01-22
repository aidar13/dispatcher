<?php

declare(strict_types=1);

namespace App\Module\Routing\Providers;

use App\Module\Routing\Contracts\Services\RoutingService as RoutingServiceContract;
use App\Module\Routing\Services\RoutingService;
use Illuminate\Support\ServiceProvider;

final class BindServiceProvider extends ServiceProvider
{
    public array $bindings = [
        RoutingServiceContract::class => RoutingService::class
    ];
}
