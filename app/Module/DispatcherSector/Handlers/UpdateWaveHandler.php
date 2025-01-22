<?php

declare(strict_types=1);

namespace App\Module\DispatcherSector\Handlers;

use App\Module\DispatcherSector\Commands\UpdateWaveCommand;
use App\Module\DispatcherSector\Contracts\Queries\WaveQuery;
use App\Module\DispatcherSector\Contracts\Repositories\UpdateWaveRepository;

final class UpdateWaveHandler
{
    public function __construct(
        private readonly WaveQuery $query,
        private readonly UpdateWaveRepository $repository,
    ) {
    }

    public function handle(UpdateWaveCommand $command): void
    {
        $wave = $this->query->getById($command->id);

        $wave->dispatcher_sector_id = $command->DTO->dispatcherSectorId;
        $wave->title                = $command->DTO->title;
        $wave->from_time            = $command->DTO->fromTime;
        $wave->to_time              = $command->DTO->toTime;

        $this->repository->update($wave);
    }
}
