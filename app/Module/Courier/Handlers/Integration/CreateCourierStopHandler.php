<?php

declare(strict_types=1);

namespace App\Module\Courier\Handlers\Integration;

use App\Module\Courier\Commands\Integration\CreateCourierStopCommand;
use App\Module\Courier\Contracts\Queries\CourierStopQuery;
use App\Module\Courier\Contracts\Repositories\CreateCourierStopRepository;
use App\Module\CourierApp\Models\CourierStop;

final class CreateCourierStopHandler
{
    public function __construct(
        private readonly CourierStopQuery $query,
        private readonly CreateCourierStopRepository $repository,
    ) {
    }

    public function handle(CreateCourierStopCommand $command): void
    {
        $model = $this->query->getById($command->DTO->id);

        if ($model) {
            return;
        }

        $model              = new CourierStop();
        $model->id          = $command->DTO->id;
        $model->courier_id  = $command->DTO->courierId;
        $model->client_id   = $command->DTO->clientId;
        $model->client_type = $command->DTO->clientType;

        $this->repository->save($model);
    }
}
