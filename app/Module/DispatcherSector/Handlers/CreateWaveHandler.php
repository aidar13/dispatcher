<?php

declare(strict_types=1);

namespace App\Module\DispatcherSector\Handlers;

use App\Module\DispatcherSector\Commands\CreateWaveCommand;
use App\Module\DispatcherSector\Contracts\Repositories\CreateWaveRepository;
use App\Module\DispatcherSector\Models\Wave;

final class CreateWaveHandler
{
    public function __construct(private readonly CreateWaveRepository $repository)
    {
    }

    public function handle(CreateWaveCommand $command): void
    {
        $wave                       = new Wave();
        $wave->dispatcher_sector_id = $command->DTO->dispatcherSectorId;
        $wave->title                = $command->DTO->title;
        $wave->from_time            = $command->DTO->fromTime;
        $wave->to_time              = $command->DTO->toTime;

        $this->repository->create($wave);
    }
}
