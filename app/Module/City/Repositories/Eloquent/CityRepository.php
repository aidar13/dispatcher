<?php

declare(strict_types=1);

namespace App\Module\City\Repositories\Eloquent;

use App\Module\City\Contracts\Repositories\CreateCityRepository;
use App\Module\City\Contracts\Repositories\UpdateCityRepository;
use App\Module\City\Models\City;

final class CityRepository implements CreateCityRepository, UpdateCityRepository
{
    public function create(City $city): void
    {
        $city->save();
    }

    public function update(City $city): void
    {
        $city->saveOrFail();
    }
}
