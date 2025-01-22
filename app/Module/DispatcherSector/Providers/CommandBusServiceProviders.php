<?php

declare(strict_types=1);

namespace App\Module\DispatcherSector\Providers;

use App\Module\DispatcherSector\Commands\AttachDispatcherSectorUsersCommand;
use App\Module\DispatcherSector\Commands\CreateDefaultSectorCommand;
use App\Module\DispatcherSector\Commands\CreateDispatcherSectorCommand;
use App\Module\DispatcherSector\Commands\CreateDispatcherSectorIntegrationCommand;
use App\Module\DispatcherSector\Commands\CreateSectorCommand;
use App\Module\DispatcherSector\Commands\CreateSectorIntegrationCommand;
use App\Module\DispatcherSector\Commands\CreateWaveCommand;
use App\Module\DispatcherSector\Commands\DeleteDispatcherSectorCommand;
use App\Module\DispatcherSector\Commands\DeleteSectorCommand;
use App\Module\DispatcherSector\Commands\DeleteWaveCommand;
use App\Module\DispatcherSector\Commands\DestroyDispatcherSectorIntegrationCommand;
use App\Module\DispatcherSector\Commands\DestroySectorIntegrationCommand;
use App\Module\DispatcherSector\Commands\Integration\CreateDispatcherSectorCommand as IntegrationCreateDispatcherSectorCommand;
use App\Module\DispatcherSector\Commands\Integration\UpdateDispatcherSectorCommand as IntegrationUpdateDispatcherSectorCommand;
use App\Module\DispatcherSector\Commands\SendSectorTo1CCommand;
use App\Module\DispatcherSector\Commands\SetSectorCoordinatesCommand;
use App\Module\DispatcherSector\Commands\UpdateDispatcherSectorCommand;
use App\Module\DispatcherSector\Commands\UpdateDispatcherSectorIntegrationCommand;
use App\Module\DispatcherSector\Commands\UpdateSectorCommand;
use App\Module\DispatcherSector\Commands\UpdateSectorIntegrationCommand;
use App\Module\DispatcherSector\Commands\UpdateWaveCommand;
use App\Module\DispatcherSector\Handlers\AttachDispatcherSectorUsersCommandHandler;
use App\Module\DispatcherSector\Handlers\CreateDefaultSectorHandler;
use App\Module\DispatcherSector\Handlers\CreateDispatcherSectorHandler;
use App\Module\DispatcherSector\Handlers\CreateDispatcherSectorIntegrationHandler;
use App\Module\DispatcherSector\Handlers\CreateSectorHandler;
use App\Module\DispatcherSector\Handlers\CreateSectorIntegrationHandler;
use App\Module\DispatcherSector\Handlers\CreateWaveHandler;
use App\Module\DispatcherSector\Handlers\DeleteDispatcherSectorHandler;
use App\Module\DispatcherSector\Handlers\DeleteSectorHandler;
use App\Module\DispatcherSector\Handlers\DeleteWaveHandler;
use App\Module\DispatcherSector\Handlers\DestroyDispatcherSectorIntegrationHandler;
use App\Module\DispatcherSector\Handlers\DestroySectorIntegrationHandler;
use App\Module\DispatcherSector\Handlers\Integration\CreateDispatcherSectorHandler as IntegrationCreateDispatcherSectorHandler;
use App\Module\DispatcherSector\Handlers\Integration\UpdateDispatcherSectorHandler as IntegrationUpdateDispatcherSectorHandler;
use App\Module\DispatcherSector\Handlers\SendSectorTo1CHandler;
use App\Module\DispatcherSector\Handlers\SetSectorCoordinatesHandler;
use App\Module\DispatcherSector\Handlers\UpdateDispatcherSectorHandler;
use App\Module\DispatcherSector\Handlers\UpdateDispatcherSectorIntegrationHandler;
use App\Module\DispatcherSector\Handlers\UpdateSectorHandler;
use App\Module\DispatcherSector\Handlers\UpdateSectorIntegrationHandler;
use App\Module\DispatcherSector\Handlers\UpdateWaveHandler;
use Illuminate\Support\Facades\Bus;
use Illuminate\Support\ServiceProvider;

final class CommandBusServiceProviders extends ServiceProvider
{
    public function boot(): void
    {
        Bus::map([
            IntegrationCreateDispatcherSectorCommand::class  => IntegrationCreateDispatcherSectorHandler::class,
            IntegrationUpdateDispatcherSectorCommand::class  => IntegrationUpdateDispatcherSectorHandler::class,
            CreateSectorCommand::class                       => CreateSectorHandler::class,
            UpdateSectorCommand::class                       => UpdateSectorHandler::class,
            DeleteSectorCommand::class                       => DeleteSectorHandler::class,
            CreateDispatcherSectorCommand::class             => CreateDispatcherSectorHandler::class,
            UpdateDispatcherSectorCommand::class             => UpdateDispatcherSectorHandler::class,
            DeleteDispatcherSectorCommand::class             => DeleteDispatcherSectorHandler::class,
            CreateWaveCommand::class                         => CreateWaveHandler::class,
            UpdateWaveCommand::class                         => UpdateWaveHandler::class,
            DeleteWaveCommand::class                         => DeleteWaveHandler::class,
            CreateDispatcherSectorIntegrationCommand::class  => CreateDispatcherSectorIntegrationHandler::class,
            UpdateDispatcherSectorIntegrationCommand::class  => UpdateDispatcherSectorIntegrationHandler::class,
            DestroyDispatcherSectorIntegrationCommand::class => DestroyDispatcherSectorIntegrationHandler::class,
            CreateSectorIntegrationCommand::class            => CreateSectorIntegrationHandler::class,
            UpdateSectorIntegrationCommand::class            => UpdateSectorIntegrationHandler::class,
            DestroySectorIntegrationCommand::class           => DestroySectorIntegrationHandler::class,
            SetSectorCoordinatesCommand::class               => SetSectorCoordinatesHandler::class,
            SendSectorTo1CCommand::class                     => SendSectorTo1CHandler::class,
            CreateDefaultSectorCommand::class                => CreateDefaultSectorHandler::class,
            AttachDispatcherSectorUsersCommand::class        => AttachDispatcherSectorUsersCommandHandler::class,
        ]);
    }
}
