<?php

declare(strict_types=1);

namespace App\Module\DispatcherSector\Handlers;

use App\Module\DispatcherSector\Commands\CreateSectorIntegrationCommand;
use App\Module\DispatcherSector\Contracts\Integrations\Repositories\CreateSectorIntegrationRepository;
use App\Module\DispatcherSector\Contracts\Queries\SectorQuery;
use App\Module\DispatcherSector\DTO\IntegrationSectorDTO;

final readonly class CreateSectorIntegrationHandler
{
    public function __construct(
        private SectorQuery $sectorQuery,
        private CreateSectorIntegrationRepository $sectorIntegrationRepository
    ) {
    }

    public function handle(CreateSectorIntegrationCommand $command): void
    {
        $sector = $this->sectorQuery->getById($command->sectorId);

        $this->sectorIntegrationRepository->create(IntegrationSectorDTO::fromModel($sector));
    }
}
