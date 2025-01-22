<?php

declare(strict_types=1);

namespace App\Module\RabbitMQ\Handlers;

use App\Module\RabbitMQ\Commands\RabbitMQRequestCreatedCommand;
use App\Module\RabbitMQ\Contracts\Queries\RabbitMQRequestQuery;
use App\Module\RabbitMQ\Contracts\Services\RabbitMQService;

final class RabbitMQRequestCreatedHandler
{
    public function __construct(
        private readonly RabbitMQRequestQuery $query,
        private readonly RabbitMQService $service,
    ) {
    }

    public function handle(RabbitMQRequestCreatedCommand $command): void
    {
        $request = $this->query->getById($command->requestId);

        $this->service->send($request);
    }
}
