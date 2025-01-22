<?php

declare(strict_types=1);

namespace App\Module\DispatcherSector\Handlers;

use App\Module\DispatcherSector\Commands\DeleteWaveCommand;
use App\Module\DispatcherSector\Contracts\Queries\WaveQuery;
use App\Module\DispatcherSector\Contracts\Repositories\RemoveWaveRepository;

final class DeleteWaveHandler
{
    public function __construct(
        private readonly WaveQuery $query,
        private readonly RemoveWaveRepository $repository,
    ) {
    }

    /**
     * @param DeleteWaveCommand $command
     * @return void
     */
    public function handle(DeleteWaveCommand $command): void
    {
        $wave = $this->query->getById($command->id);

        $this->repository->remove($wave);
    }
}
