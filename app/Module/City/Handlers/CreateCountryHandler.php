<?php

declare(strict_types=1);

namespace App\Module\City\Handlers;

use App\Module\City\Commands\CreateCountryCommand;
use App\Module\City\Contracts\Queries\CountryQuery;
use App\Module\City\Contracts\Repositories\CreateCountryRepository;
use App\Module\City\Models\Country;
use Illuminate\Database\Eloquent\ModelNotFoundException;

final class CreateCountryHandler
{
    public function __construct(
        private readonly CreateCountryRepository $createCountryRepository,
        private readonly CountryQuery $countryQuery
    ) {
    }

    public function handle(CreateCountryCommand $command): void
    {
        if ($this->countryExists($command->DTO->id)) {
            return;
        }

        $country = new Country();

        $country->id    = $command->DTO->id;
        $country->name  = $command->DTO->name;
        $country->title = $command->DTO->title;

        $this->createCountryRepository->create($country);
    }

    private function countryExists(int $id): bool
    {
        try {
            $this->countryQuery->getById($id);

            return true;
        } catch (ModelNotFoundException) {
            return false;
        }
    }
}
