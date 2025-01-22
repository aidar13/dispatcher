<?php

declare(strict_types=1);

namespace App\Module\RabbitMQ\Listeners;

use App\Module\RabbitMQ\Commands\RabbitMQRequestStatusCommand;
use App\Module\RabbitMQ\Events\RabbitMQRequestStatusEvent;

final class RabbitMQRequestStatusListener
{
    public function handle(RabbitMQRequestStatusEvent $event): void
    {
        dispatch(new RabbitMQRequestStatusCommand(
            $event->requestId,
            $event->successAt,
            $event->failedAt,
            $event->message
        ));
    }
}
