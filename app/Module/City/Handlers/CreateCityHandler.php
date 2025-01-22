<?php

declare(strict_types=1);

namespace App\Module\City\Handlers;

use App\Module\City\Commands\CreateCityCommand;
use App\Module\City\Contracts\Queries\CityQuery;
use App\Module\City\Contracts\Repositories\CreateCityRepository;
use App\Module\City\Models\City;
use Illuminate\Database\Eloquent\ModelNotFoundException;

final class CreateCityHandler
{
    public function __construct(
        private readonly CreateCityRepository $createCityRepository,
        private readonly CityQuery $cityQuery
    ) {
    }

    public function handle(CreateCityCommand $command): void
    {
        if ($this->cityExists($command->DTO->id)) {
            return;
        }

        $city = new City();

        $city->id          = $command->DTO->id;
        $city->name        = $command->DTO->name;
        $city->region_id   = $command->DTO->regionId;
        $city->type_id     = $command->DTO->typeId;
        $city->code        = $command->DTO->code;
        $city->longitude   = $command->DTO->longitude;
        $city->latitude    = $command->DTO->latitude;
        $city->coordinates = $command->DTO->coordinates;

        $this->createCityRepository->create($city);
    }

    private function cityExists(int $id): bool
    {
        try {
            $this->cityQuery->getById($id);
            return true;
        } catch (ModelNotFoundException) {
            return false;
        }
    }
}
