<?php

declare(strict_types=1);

namespace App\Module\City\Contracts\Repositories;

use App\Module\City\Models\City;

interface CreateCityRepository
{
    public function create(City $city): void;
}
