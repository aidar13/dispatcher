<?php

declare(strict_types=1);

namespace App\Module\City\Providers;

use App\Module\City\Commands\CreateCityCommand;
use App\Module\City\Commands\CreateCountryCommand;
use App\Module\City\Commands\CreateRegionCommand;
use App\Module\City\Commands\UpdateCityCommand;
use App\Module\City\Handlers\CreateCityHandler;
use App\Module\City\Handlers\CreateCountryHandler;
use App\Module\City\Handlers\CreateRegionHandler;
use App\Module\City\Handlers\UpdateCityHandler;
use Illuminate\Support\Facades\Bus;
use Illuminate\Support\ServiceProvider;

final class CommandBusServiceProviders extends ServiceProvider
{
    public function boot(): void
    {
        Bus::map(array(
            CreateCityCommand::class    => CreateCityHandler::class,
            UpdateCityCommand::class    => UpdateCityHandler::class,
            CreateRegionCommand::class  => CreateRegionHandler::class,
            CreateCountryCommand::class => CreateCountryHandler::class,
        ));
    }
}
