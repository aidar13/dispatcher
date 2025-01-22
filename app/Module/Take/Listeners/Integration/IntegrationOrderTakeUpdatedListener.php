<?php

declare(strict_types=1);

namespace App\Module\Take\Listeners\Integration;

use App\Module\RabbitMQ\Commands\CreateRabbitMQRequestCommand;
use App\Module\RabbitMQ\DTO\CreateRabbitMQRequestDTO;
use Illuminate\Bus\Dispatcher;

final class IntegrationOrderTakeUpdatedListener
{
    public function __construct(private readonly Dispatcher $dispatcher)
    {
    }

    public function handle($event): void
    {
        $this->dispatcher->dispatchNow(new CreateRabbitMQRequestCommand(
            CreateRabbitMQRequestDTO::fromEvent($event)
        ));
    }
}
