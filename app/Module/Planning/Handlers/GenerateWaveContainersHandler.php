<?php

declare(strict_types=1);

namespace App\Module\Planning\Handlers;

use App\Module\Planning\Commands\GenerateSectorContainersCommand;
use App\Module\Planning\Commands\GenerateWaveContainersCommand;
use App\Module\DispatcherSector\Contracts\Queries\SectorQuery;
use App\Module\DispatcherSector\Models\Sector;
use Illuminate\Bus\Dispatcher;

final class GenerateWaveContainersHandler
{
    public function __construct(
        private readonly Dispatcher $dispatcher,
        private readonly SectorQuery $sectorQuery
    ) {
    }

    public function handle(GenerateWaveContainersCommand $command): void
    {
        $sectors = $this->sectorQuery->getAllByDispatcherSectorIdAndIds(
            $command->DTO->dispatcherSectorId,
            $command->DTO->sectorIds
        );

        /** @var Sector $sector */
        foreach ($sectors as $sector) {
            $this->dispatcher->dispatch(new GenerateSectorContainersCommand(
                $sector->id,
                $command->DTO->waveId,
                $command->DTO->date,
                $command->userId,
                $command->DTO->statusId
            ));
        }
    }
}
