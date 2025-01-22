<?php

declare(strict_types=1);

namespace App\Module\Routing\Providers;

use App\Module\Routing\Commands\CreateCourierRoutingCommand;
use App\Module\Routing\Commands\CreateRoutingForDispatcherSectorCommand;
use App\Module\Routing\Commands\CreateRoutingItemsCommand;
use App\Module\Routing\Commands\CreateSectorInYandexCommand;
use App\Module\Routing\Commands\DeleteSectorInYandexCommand;
use App\Module\Routing\Commands\SendRoutingCommand;
use App\Module\Routing\Commands\UpdateRoutingItemPositionsCommand;
use App\Module\Routing\Commands\UpdateSectorInYandexCommand;
use App\Module\Routing\Handlers\CreateCourierRoutingHandler;
use App\Module\Routing\Handlers\CreateRoutingForDispatcherHandler;
use App\Module\Routing\Handlers\CreateRoutingItemsHandler;
use App\Module\Routing\Handlers\CreateSectorInYandexHandler;
use App\Module\Routing\Handlers\DeleteSectorInYandexHandler;
use App\Module\Routing\Handlers\SendRoutingHandler;
use App\Module\Routing\Handlers\UpdateRoutingItemPositionsHandler;
use App\Module\Routing\Handlers\UpdateSectorInYandexHandler;
use Illuminate\Support\Facades\Bus;
use Illuminate\Support\ServiceProvider;

final class CommandBusServiceProviders extends ServiceProvider
{
    private array $maps = [
        //routing
        CreateCourierRoutingCommand::class             => CreateCourierRoutingHandler::class,
        CreateRoutingItemsCommand::class               => CreateRoutingItemsHandler::class,
        SendRoutingCommand::class                      => SendRoutingHandler::class,
        CreateRoutingForDispatcherSectorCommand::class => CreateRoutingForDispatcherHandler::class,
        UpdateRoutingItemPositionsCommand::class       => UpdateRoutingItemPositionsHandler::class,

        CreateSectorInYandexCommand::class => CreateSectorInYandexHandler::class,
        UpdateSectorInYandexCommand::class => UpdateSectorInYandexHandler::class,
        DeleteSectorInYandexCommand::class => DeleteSectorInYandexHandler::class,
    ];

    public function boot(): void
    {
        $this->registerCommandHandlers();
    }

    private function registerCommandHandlers(): void
    {
        Bus::map($this->maps);
    }
}
