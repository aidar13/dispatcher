<?php

declare(strict_types=1);

namespace App\Module\Planning\Handlers;

use App\Module\Planning\Commands\ChangeContainerStatusCommand;
use App\Module\Planning\Contracts\Queries\ContainerQuery;
use App\Module\Planning\Contracts\Repositories\UpdateContainerRepository;
use App\Module\Planning\Events\ContainerStatusUpdatedEvent;

final class ChangeContainerStatusHandler
{
    public function __construct(
        private readonly ContainerQuery $query,
        private readonly UpdateContainerRepository $repository
    ) {
    }

    public function handle(ChangeContainerStatusCommand $command): void
    {
        $container = $this->query->getById($command->DTO->containerId);

        $container->status_id = $command->DTO->containerStatusId;

        $this->repository->update($container);

        event(new ContainerStatusUpdatedEvent($command->DTO));
    }
}
