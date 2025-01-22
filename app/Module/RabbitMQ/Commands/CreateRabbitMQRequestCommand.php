<?php

declare(strict_types=1);

namespace App\Module\RabbitMQ\Commands;

use App\Module\RabbitMQ\DTO\CreateRabbitMQRequestDTO;

final class CreateRabbitMQRequestCommand
{
    public function __construct(public CreateRabbitMQRequestDTO $DTO)
    {
    }
}
