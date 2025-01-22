<?php

declare(strict_types=1);

namespace App\Module\DispatcherSector\Handlers;

use App\Helpers\PolygonHelper;
use App\Module\DispatcherSector\Commands\AttachDispatcherSectorUsersCommand;
use App\Module\DispatcherSector\Commands\CreateDispatcherSectorCommand;
use App\Module\DispatcherSector\Contracts\Repositories\CreateDispatcherSectorRepository;
use App\Module\DispatcherSector\Events\DispatcherSectorCreatedEvent;
use App\Module\DispatcherSector\Models\DispatcherSector;

final class CreateDispatcherSectorHandler
{
    public function __construct(
        private readonly CreateDispatcherSectorRepository $createDispatcherSectorRepository,
    ) {
    }

    public function handle(CreateDispatcherSectorCommand $command): void
    {
        $dispatcherSector = new DispatcherSector();

        $dispatcherSector->name                = $command->DTO->name;
        $dispatcherSector->city_id             = $command->DTO->cityId;
        $dispatcherSector->delivery_manager_id = $command->DTO->deliveryManagerId;
        $dispatcherSector->description         = $command->DTO->description;
        $dispatcherSector->coordinates         = json_encode($command->DTO->coordinates);
        $dispatcherSector->polygon             = PolygonHelper::getPolygonFromCoordinates($command->DTO->coordinates);

        $this->createDispatcherSectorRepository->create($dispatcherSector);

        dispatch(new AttachDispatcherSectorUsersCommand($dispatcherSector->id, $command->DTO->dispatcherIds));

        event(new DispatcherSectorCreatedEvent($dispatcherSector->id));
    }
}
