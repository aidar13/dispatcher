<?php

declare(strict_types=1);

namespace App\Module\CourierApp\Handlers\CourierCall;

use App\Module\Courier\Contracts\Queries\CourierQuery;
use App\Module\CourierApp\Commands\CourierCall\CreateCourierCallCommand;
use App\Module\CourierApp\Models\CourierCall;
use App\Module\CourierApp\Repositories\CourierCall\CourierCallRepository;

final class CreateCourierCallHandler
{
    public function __construct(
        private readonly CourierQuery $query,
        private readonly CourierCallRepository $repository,
    ) {
    }

    public function handle(CreateCourierCallCommand $command): void
    {
        $courier = $this->query->getByUserId($command->userId);

        $courierCall              = new CourierCall();
        $courierCall->courier_id  = $courier->id;
        $courierCall->client_id   = $command->DTO->clientId;
        $courierCall->client_type = $command->DTO->clientType;
        $courierCall->phone       = $command->DTO->phone;

        $this->repository->create($courierCall);
    }
}
