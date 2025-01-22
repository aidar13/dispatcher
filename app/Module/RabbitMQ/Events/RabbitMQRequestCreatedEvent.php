<?php

namespace App\Module\RabbitMQ\Events;

final class RabbitMQRequestCreatedEvent
{
    public function __construct(public readonly int $requestId)
    {
    }
}
