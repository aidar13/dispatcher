<?php

declare(strict_types=1);

namespace App\Module\DispatcherSector\Providers;

use App\Module\DispatcherSector\Contracts\Services\DispatcherSectorService as DispatcherSectorServiceContract;
use App\Module\DispatcherSector\Contracts\Services\SectorService as SectorServiceContract;
use App\Module\DispatcherSector\Contracts\Services\WaveService as WaveServiceServiceContract;
use App\Module\DispatcherSector\Contracts\Services\GetUserEmailService as GetUserEmailServiceContract;
use App\Module\DispatcherSector\Services\DispatcherSectorService;
use App\Module\DispatcherSector\Services\Integration\GetUserEmailService;
use App\Module\DispatcherSector\Services\SectorService;
use App\Module\DispatcherSector\Services\WaveService;
use Illuminate\Support\ServiceProvider;

class BindServiceProvider extends ServiceProvider
{
    public array $bindings = [
        SectorServiceContract::class           => SectorService::class,
        DispatcherSectorServiceContract::class => DispatcherSectorService::class,
        WaveServiceServiceContract::class      => WaveService::class,
        GetUserEmailServiceContract::class     => GetUserEmailService::class,
    ];
}
