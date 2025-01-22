<?php

declare(strict_types=1);

namespace App\Module\Courier\Handlers;

use App\Module\Courier\Commands\CreateCourierStopCommand;
use App\Module\Courier\Contracts\Repositories\CreateCourierStopRepository;
use App\Module\CourierApp\Models\CourierStop;

final class CreateCourierStopHandler
{
    public function __construct(
        private readonly CreateCourierStopRepository $repository,
    ) {
    }

    public function handle(CreateCourierStopCommand $command): void
    {
        $stop              = new CourierStop();
        $stop->courier_id  = $command->courierId;
        $stop->client_id   = $command->clientId;
        $stop->client_type = $command->clientType;

        $this->repository->save($stop);
    }
}
