<?php

declare(strict_types=1);

namespace App\Module\City\Handlers;

use App\Module\City\Commands\UpdateCityCommand;
use App\Module\City\Contracts\Queries\CityQuery;
use App\Module\City\Contracts\Repositories\UpdateCityRepository;

final class UpdateCityHandler
{
    public function __construct(
        private readonly UpdateCityRepository $repository,
        private readonly CityQuery $cityQuery
    ) {
    }

    public function handle(UpdateCityCommand $command): void
    {
        $city              = $this->cityQuery->getById($command->DTO->id);

        $city->name        = $command->DTO->name;
        $city->region_id   = $command->DTO->regionId;
        $city->type_id     = $command->DTO->typeId;
        $city->code        = $command->DTO->code;
        $city->longitude   = $command->DTO->longitude;
        $city->latitude    = $command->DTO->latitude;
        $city->coordinates = $command->DTO->coordinates;

        $this->repository->update($city);
    }
}
