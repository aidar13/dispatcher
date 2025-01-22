<?php

declare(strict_types=1);

namespace App\Module\DispatcherSector\Providers;

use App\Module\DispatcherSector\Contracts\Integrations\Repositories\SendSectorTo1CRepository as SendSectorTo1CRepositoryContract;
use App\Module\DispatcherSector\Contracts\Integrations\Repositories\CreateDispatcherSectorIntegrationRepository;
use App\Module\DispatcherSector\Contracts\Integrations\Repositories\CreateSectorIntegrationRepository;
use App\Module\DispatcherSector\Contracts\Integrations\Repositories\DestroyDispatcherSectorIntegrationRepository;
use App\Module\DispatcherSector\Contracts\Integrations\Repositories\DestroySectorIntegrationRepository;
use App\Module\DispatcherSector\Contracts\Integrations\Repositories\UpdateDispatcherSectorIntegrationRepository;
use App\Module\DispatcherSector\Contracts\Integrations\Repositories\UpdateSectorIntegrationRepository;
use App\Module\DispatcherSector\Contracts\Repositories\AttachDispatcherSectorUsersRepository;
use App\Module\DispatcherSector\Contracts\Repositories\CreateDispatcherSectorRepository;
use App\Module\DispatcherSector\Contracts\Repositories\CreateSectorRepository;
use App\Module\DispatcherSector\Contracts\Repositories\CreateWaveRepository;
use App\Module\DispatcherSector\Contracts\Repositories\RemoveDispatcherSectorRepository;
use App\Module\DispatcherSector\Contracts\Repositories\RemoveSectorRepository;
use App\Module\DispatcherSector\Contracts\Repositories\RemoveWaveRepository;
use App\Module\DispatcherSector\Contracts\Repositories\UpdateDispatcherSectorRepository;
use App\Module\DispatcherSector\Contracts\Repositories\UpdateSectorRepository;
use App\Module\DispatcherSector\Contracts\Repositories\UpdateWaveRepository;
use App\Module\DispatcherSector\Repositories\Eloquent\DispatcherSectorRepository;
use App\Module\DispatcherSector\Repositories\Eloquent\SectorRepository;
use App\Module\DispatcherSector\Repositories\Eloquent\WaveRepository;
use App\Module\DispatcherSector\Repositories\Integrations\DispatcherSectorIntegrationRepository;
use App\Module\DispatcherSector\Repositories\Integrations\SectorIntegrationRepository;
use App\Module\DispatcherSector\Repositories\Integrations\SendSectorTo1CRepository;
use Illuminate\Support\ServiceProvider;

final class RepositoryServiceProvider extends ServiceProvider
{
    public array $bindings = [
        UpdateDispatcherSectorRepository::class             => DispatcherSectorRepository::class,
        CreateDispatcherSectorRepository::class             => DispatcherSectorRepository::class,
        RemoveDispatcherSectorRepository::class             => DispatcherSectorRepository::class,
        AttachDispatcherSectorUsersRepository::class        => DispatcherSectorRepository::class,
        CreateSectorRepository::class                       => SectorRepository::class,
        UpdateSectorRepository::class                       => SectorRepository::class,
        RemoveSectorRepository::class                       => SectorRepository::class,
        CreateWaveRepository::class                         => WaveRepository::class,
        UpdateWaveRepository::class                         => WaveRepository::class,
        RemoveWaveRepository::class                         => WaveRepository::class,
        CreateDispatcherSectorIntegrationRepository::class  => DispatcherSectorIntegrationRepository::class,
        UpdateDispatcherSectorIntegrationRepository::class  => DispatcherSectorIntegrationRepository::class,
        DestroyDispatcherSectorIntegrationRepository::class => DispatcherSectorIntegrationRepository::class,
        CreateSectorIntegrationRepository::class            => SectorIntegrationRepository::class,
        UpdateSectorIntegrationRepository::class            => SectorIntegrationRepository::class,
        DestroySectorIntegrationRepository::class           => SectorIntegrationRepository::class,
        SendSectorTo1CRepositoryContract::class             => SendSectorTo1CRepository::class,
    ];

    public function register(): void
    {
    }
}
