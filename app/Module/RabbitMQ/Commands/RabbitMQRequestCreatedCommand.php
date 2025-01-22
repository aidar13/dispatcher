<?php

declare(strict_types=1);

namespace App\Module\RabbitMQ\Commands;

final class RabbitMQRequestCreatedCommand
{
    public function __construct(public int $requestId)
    {
    }
}
