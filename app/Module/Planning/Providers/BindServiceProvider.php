<?php

declare(strict_types=1);

namespace App\Module\Planning\Providers;

use App\Module\Planning\Contracts\Services\ContainerService as ContainerServiceContract;
use App\Module\Planning\Contracts\Services\PlanningService as PlanningServiceContract;
use App\Module\Planning\Services\ContainerService;
use App\Module\Planning\Services\PlanningService;
use Illuminate\Support\ServiceProvider;

class BindServiceProvider extends ServiceProvider
{
    public array $bindings = [
        PlanningServiceContract::class     => PlanningService::class,
        ContainerServiceContract::class    => ContainerService::class,
    ];
}
