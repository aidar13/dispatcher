<?php

declare(strict_types=1);

namespace App\Module\RabbitMQ\Handlers;

use App\Module\RabbitMQ\Commands\CreateRabbitMQRequestCommand;
use App\Module\RabbitMQ\Contracts\Repositories\CreateRabbitMQRequestRepository;
use App\Module\RabbitMQ\Models\RabbitMQRequest;

final class CreateRabbitMQRequestHandler
{
    public function __construct(
        private readonly CreateRabbitMQRequestRepository $repository
    ) {
    }

    public function handle(CreateRabbitMQRequestCommand $command): void
    {
        $request          = new RabbitMQRequest();
        $request->data    = $command->DTO->data;
        $request->channel = $command->DTO->channel;

        $this->repository->create($request);
    }
}
