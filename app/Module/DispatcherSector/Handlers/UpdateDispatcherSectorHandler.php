<?php

declare(strict_types=1);

namespace App\Module\DispatcherSector\Handlers;

use App\Helpers\PolygonHelper;
use App\Module\DispatcherSector\Commands\AttachDispatcherSectorUsersCommand;
use App\Module\DispatcherSector\Commands\UpdateDispatcherSectorCommand;
use App\Module\DispatcherSector\Contracts\Queries\DispatcherSectorQuery;
use App\Module\DispatcherSector\Contracts\Repositories\UpdateDispatcherSectorRepository;
use App\Module\DispatcherSector\Events\DispatcherSectorUpdatedEvent;

final class UpdateDispatcherSectorHandler
{
    public function __construct(
        private readonly DispatcherSectorQuery $dispatcherSectorQuery,
        private readonly UpdateDispatcherSectorRepository $updateDispatcherSectorRepository,
    ) {
    }

    public function handle(UpdateDispatcherSectorCommand $command): void
    {
        $dispatcherSector = $this->dispatcherSectorQuery->getById($command->dispatchersSectorId);

        $dispatcherSector->name                = $command->DTO->name;
        $dispatcherSector->city_id             = $command->DTO->cityId;
        $dispatcherSector->description         = $command->DTO->description;
        $dispatcherSector->delivery_manager_id = $command->DTO->deliveryManagerId;
        $dispatcherSector->coordinates         = json_encode($command->DTO->coordinates);
        $dispatcherSector->polygon             = PolygonHelper::getPolygonFromCoordinates($command->DTO->coordinates);

        $this->updateDispatcherSectorRepository->update($dispatcherSector);

        dispatch(new AttachDispatcherSectorUsersCommand($dispatcherSector->id, $command->DTO->dispatcherIds));

        event(new DispatcherSectorUpdatedEvent($dispatcherSector->id));
    }
}
