<?php

declare(strict_types=1);

namespace App\Module\RabbitMQ\Providers;

use App\Module\RabbitMQ\Commands\CreateRabbitMQRequestCommand;
use App\Module\RabbitMQ\Commands\RabbitMQRequestCreatedCommand;
use App\Module\RabbitMQ\Commands\RabbitMQRequestStatusCommand;
use App\Module\RabbitMQ\Handlers\CreateRabbitMQRequestHandler;
use App\Module\RabbitMQ\Handlers\RabbitMQRequestCreatedHandler;
use App\Module\RabbitMQ\Handlers\RabbitMQRequestStatusHandler;
use Illuminate\Support\Facades\Bus;
use Illuminate\Support\ServiceProvider;

final class CommandBusServiceProviders extends ServiceProvider
{
    public function boot(): void
    {
        Bus::map(array(
            CreateRabbitMQRequestCommand::class  => CreateRabbitMQRequestHandler::class,
            RabbitMQRequestCreatedCommand::class => RabbitMQRequestCreatedHandler::class,
            RabbitMQRequestStatusCommand::class  => RabbitMQRequestStatusHandler::class,
        ));
    }
}
