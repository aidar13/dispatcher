<?php

declare(strict_types=1);

namespace App\Module\Planning\Handlers;

use App\Module\Planning\Commands\UpdateContainerStatusCommand;
use App\Module\Planning\Contracts\Queries\ContainerQuery;
use App\Module\Planning\Contracts\Repositories\UpdateContainerRepository;
use Illuminate\Support\Facades\Log;

final class UpdateContainerStatusHandler
{
    public function __construct(
        private readonly ContainerQuery $containerQuery,
        private readonly UpdateContainerRepository $containerRepository,
    ) {
    }

    public function handle(UpdateContainerStatusCommand $command): void
    {
        $container = $this->containerQuery->getById($command->containerId);

        $container->status_id = $command->statusId;

        $this->containerRepository->update($container);

        Log::info('Изменен статус контейнера', [
            'containerId' => $container->id,
            'statusId'    => $command->statusId
        ]);
    }
}
