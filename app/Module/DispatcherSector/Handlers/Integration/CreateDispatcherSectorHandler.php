<?php

declare(strict_types=1);

namespace App\Module\DispatcherSector\Handlers\Integration;

use App\Helpers\PolygonHelper;
use App\Module\DispatcherSector\Commands\Integration\CreateDispatcherSectorCommand;
use App\Module\DispatcherSector\Contracts\Queries\DispatcherSectorQuery;
use App\Module\DispatcherSector\Contracts\Repositories\CreateDispatcherSectorRepository;
use App\Module\DispatcherSector\Models\DispatcherSector;
use Illuminate\Database\Eloquent\ModelNotFoundException;

final class CreateDispatcherSectorHandler
{
    public function __construct(
        private readonly DispatcherSectorQuery $dispatcherSectorQuery,
        private readonly CreateDispatcherSectorRepository $createDispatcherSectorRepository,
    ) {
    }

    public function handle(CreateDispatcherSectorCommand $command): void
    {
        if ($this->dispatcherSectorExists($command->DTO->id)) {
            return;
        }

        $dispatcherSector = new DispatcherSector();

        $dispatcherSector->id          = $command->DTO->id;
        $dispatcherSector->name        = $command->DTO->name;
        $dispatcherSector->city_id     = $command->DTO->cityId;
        $dispatcherSector->description = $command->DTO->description;
        $dispatcherSector->created_at  = $command->DTO->createdAt;
        $dispatcherSector->coordinates = $command->DTO->coordinates;
        $dispatcherSector->polygon     = PolygonHelper::getPolygonFromCoordinates(json_decode($command->DTO->coordinates));

        $this->createDispatcherSectorRepository->create($dispatcherSector);

        // TODO: attach userIDs
    }

    private function dispatcherSectorExists(int $id): bool
    {
        try {
            $this->dispatcherSectorQuery->getById($id);

            return true;
        } catch (ModelNotFoundException) {
            return false;
        }
    }
}
