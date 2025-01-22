<?php

declare(strict_types=1);

namespace App\Module\Routing\Handlers;

use App\Module\DispatcherSector\Contracts\Queries\SectorQuery;
use App\Module\Routing\Commands\UpdateSectorInYandexCommand;
use App\Module\Routing\Contracts\Repositories\IntegrationZoneRepository;

final readonly class UpdateSectorInYandexHandler
{
    public function __construct(
        private SectorQuery $query,
        private IntegrationZoneRepository $repository,
    ) {
    }

    public function handle(UpdateSectorInYandexCommand $command): void
    {
        $sector = $this->query->getById($command->id);

        $this->repository->update($sector);
    }
}
