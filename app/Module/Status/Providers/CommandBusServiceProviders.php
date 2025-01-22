<?php

declare(strict_types=1);

namespace App\Module\Status\Providers;

use App\Module\Status\Commands\Integration\CreateOrderStatusCommand;
use App\Module\Status\Commands\Integration\CreateWaitListStatusCommand;
use App\Module\Status\Commands\Integration\IntegrationCreateWaitListStatusCommand;
use App\Module\Status\Commands\SendOrderStatusToCabinetCommand;
use App\Module\Status\Handlers\Integration\CreateOrderStatusHandler;
use App\Module\Status\Handlers\Integration\CreateWaitListStatusHandler;
use App\Module\Status\Handlers\Integration\IntegrationCreateWaitListStatusHandler;
use App\Module\Status\Handlers\SendOrderStatusToCabinetHandler;
use Illuminate\Support\Facades\Bus;
use Illuminate\Support\ServiceProvider;

final class CommandBusServiceProviders extends ServiceProvider
{
    public function boot(): void
    {
        Bus::map(array(
            CreateOrderStatusCommand::class           => CreateOrderStatusHandler::class,
            CreateWaitListStatusCommand::class        => CreateWaitListStatusHandler::class,
            SendOrderStatusToCabinetCommand::class    => SendOrderStatusToCabinetHandler::class,
            IntegrationCreateWaitListStatusCommand::class => IntegrationCreateWaitListStatusHandler::class,
        ));
    }
}
