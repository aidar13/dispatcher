<?php

declare(strict_types=1);

namespace App\Module\Routing\Providers;

use App\Module\Routing\Contracts\Repositories\CreateRoutingItemRepository;
use App\Module\Routing\Contracts\Repositories\CreateRoutingRepository;
use App\Module\Routing\Contracts\Repositories\IntegrationRoutingRepository as IntegrationRoutingRepositoryContract;
use App\Module\Routing\Contracts\Repositories\IntegrationZoneRepository as IntegrationZoneRepositoryContract;
use App\Module\Routing\Contracts\Repositories\UpdateRoutingItemRepository;
use App\Module\Routing\Contracts\Repositories\UpdateRoutingRepository;
use App\Module\Routing\Repositories\IntegrationRoutingRepository;
use App\Module\Routing\Repositories\IntegrationZoneRepository;
use App\Module\Routing\Repositories\RoutingItemRepository;
use App\Module\Routing\Repositories\RoutingRepository;
use Illuminate\Support\ServiceProvider;

final class RepositoryServiceProvider extends ServiceProvider
{
    public array $bindings = [
        CreateRoutingRepository::class     => RoutingRepository::class,
        UpdateRoutingRepository::class     => RoutingRepository::class,
        CreateRoutingItemRepository::class => RoutingItemRepository::class,
        UpdateRoutingItemRepository::class => RoutingItemRepository::class,

        IntegrationRoutingRepositoryContract::class => IntegrationRoutingRepository::class,
        IntegrationZoneRepositoryContract::class    => IntegrationZoneRepository::class,
    ];
}
