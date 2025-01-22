<?php

declare(strict_types=1);

namespace App\Module\Car\Providers;

use App\Module\Car\Commands\CreateCarCommand;
use App\Module\Car\Commands\CreateCarOccupancyCommand;
use App\Module\Car\Commands\UpdateCarCommand;
use App\Module\Car\Handlers\CreateCarHandler;
use App\Module\Car\Handlers\CreateCarOccupancyHandler;
use App\Module\Car\Handlers\UpdateCarHandler;
use Illuminate\Support\Facades\Bus;
use Illuminate\Support\ServiceProvider;

final class CommandBusServiceProviders extends ServiceProvider
{
    public function boot(): void
    {
        Bus::map(array(
            CreateCarCommand::class => CreateCarHandler::class,
            UpdateCarCommand::class => UpdateCarHandler::class,

            //Car Occupancy
            CreateCarOccupancyCommand::class => CreateCarOccupancyHandler::class,
        ));
    }
}
