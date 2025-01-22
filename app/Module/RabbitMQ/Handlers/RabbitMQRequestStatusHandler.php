<?php

declare(strict_types=1);

namespace App\Module\RabbitMQ\Handlers;

use App\Module\RabbitMQ\Commands\RabbitMQRequestStatusCommand;
use App\Module\RabbitMQ\Contracts\Queries\RabbitMQRequestQuery;
use App\Module\RabbitMQ\Contracts\Repositories\UpdateRabbitMQRequestRepository;
use App\Module\RabbitMQ\Models\RabbitMQRequest;

final class RabbitMQRequestStatusHandler
{
    public function __construct(
        private readonly RabbitMQRequestQuery $query,
        private readonly UpdateRabbitMQRequestRepository $repository,
    ) {
    }

    public function handle(RabbitMQRequestStatusCommand $command): void
    {
        /** @var RabbitMQRequest $request */
        $request = $this->query->getById($command->requestId);

        $request->success_at = $command->successAt;
        $request->failed_at = $command->failedAt;
        $request->error = $command->message;

        $this->repository->update($request);
    }
}
