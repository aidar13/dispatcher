<?php

declare(strict_types=1);

namespace App\Module\CourierApp\Handlers\CourierLocation;

use App\Module\Courier\Contracts\Queries\CourierQuery;
use App\Module\CourierApp\Commands\CourierLocation\CreateCourierLocationCommand;
use App\Module\CourierApp\Contracts\Queries\CourierLocation\CourierLocationQuery;
use App\Module\CourierApp\Contracts\Repositories\CourierLocation\CreateCourierLocationRepository;
use App\Module\CourierApp\Models\CourierLoaction;
use Carbon\Carbon;

final readonly class CreateCourierLocationHandler
{
    public function __construct(
        private CreateCourierLocationRepository $repository,
        private CourierLocationQuery $query,
        private CourierQuery $courierQuery,
    ) {
    }

    public function handle(CreateCourierLocationCommand $command): void
    {
        $time = $command->DTO->time
            ? Carbon::parse($command->DTO->time)
            : Carbon::now();

        $courier  = $this->courierQuery->getByUserId($command->userId);
        $location = $this->query->getFirstNearbyLocationByCourierId($courier->id, $time, $command->DTO->latitude, $command->DTO->longitude);

        $courierLocation             = new CourierLoaction();
        $courierLocation->courier_id = $courier->id;
        $courierLocation->latitude   = $command->DTO->latitude;
        $courierLocation->longitude  = $command->DTO->longitude;
        $courierLocation->downtime   = $location?->created_at->diffInMinutes(now());
        $courierLocation->created_at = $time;

        $this->repository->create($courierLocation);
    }
}
