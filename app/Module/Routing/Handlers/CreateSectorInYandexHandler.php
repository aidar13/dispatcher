<?php

declare(strict_types=1);

namespace App\Module\Routing\Handlers;

use App\Module\DispatcherSector\Contracts\Queries\SectorQuery;
use App\Module\Routing\Commands\CreateSectorInYandexCommand;
use App\Module\Routing\Contracts\Repositories\IntegrationZoneRepository;

final readonly class CreateSectorInYandexHandler
{
    public function __construct(
        private SectorQuery $query,
        private IntegrationZoneRepository $repository,
    ) {
    }

    public function handle(CreateSectorInYandexCommand $command): void
    {
        $sector = $this->query->getById($command->id);

        $this->repository->create($sector);
    }
}
