<?php

declare(strict_types=1);

namespace App\Module\RabbitMQ\Listeners;

use App\Module\RabbitMQ\Commands\RabbitMQRequestCreatedCommand;
use App\Module\RabbitMQ\Events\RabbitMQRequestCreatedEvent;
use Illuminate\Bus\Dispatcher;

final class RabbitMQRequestCreatedListener
{
    public function __construct(private readonly Dispatcher $dispatcher)
    {
    }

    public function handle(RabbitMQRequestCreatedEvent $event): void
    {
        $this->dispatcher->dispatchNow(new RabbitMQRequestCreatedCommand($event->requestId));
    }
}
