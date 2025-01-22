<?php

declare(strict_types=1);

namespace App\Module\CourierApp\Handlers\CourierState;

use App\Module\Courier\Contracts\Queries\CourierQuery;
use App\Module\CourierApp\Commands\CourierState\CreateCourierStateCommand;
use App\Module\CourierApp\Contracts\Repositories\CourierState\CreateCourierStateRepository;
use App\Module\CourierApp\Models\CourierState;

final class CreateCourierStateHandler
{
    public function __construct(
        private readonly CreateCourierStateRepository $repository,
        private readonly CourierQuery $query,
    ) {
    }

    public function handle(CreateCourierStateCommand $command): void
    {
        $courier = $this->query->getByUserId($command->userId);

        $courierState              = new CourierState();
        $courierState->courier_id  = $courier->id;
        $courierState->client_id   = $command->DTO->clientId;
        $courierState->client_type = $command->DTO->clientType;
        $courierState->latitude    = $command->DTO->latitude;
        $courierState->longitude   = $command->DTO->longitude;

        $this->repository->create($courierState);
    }
}
