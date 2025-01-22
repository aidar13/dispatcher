<?php

declare(strict_types=1);

namespace App\Module\Planning\Handlers;

use App\Module\Planning\Commands\UpdateContainerNumberCommand;
use App\Module\Planning\Contracts\Queries\ContainerQuery;
use App\Module\Planning\Contracts\Repositories\UpdateContainerRepository;
use Illuminate\Support\Facades\Log;

final class UpdateContainerNumberHandler
{
    public function __construct(
        private readonly ContainerQuery $containerQuery,
        private readonly UpdateContainerRepository $containerRepository,
    ) {
    }

    public function handle(UpdateContainerNumberCommand $command): void
    {
        $container = $this->containerQuery->getById($command->containerId);

        $container->doc_number = $command->docNumber;

        $this->containerRepository->update($container);

        Log::info('Изменен doc_number контейнера', [
            'containerId' => $container->id,
            'statusId'    => $command->docNumber
        ]);
    }
}
