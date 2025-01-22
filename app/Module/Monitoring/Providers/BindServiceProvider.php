<?php

declare(strict_types=1);

namespace App\Module\Monitoring\Providers;

use App\Module\Monitoring\Contracts\Services\MonitoringService as MonitoringServiceContract;
use App\Module\Monitoring\Services\MonitoringService;
use Illuminate\Support\ServiceProvider;

final class BindServiceProvider extends ServiceProvider
{
    public array $bindings = [
        // Services
        MonitoringServiceContract::class => MonitoringService::class,
    ];
}
