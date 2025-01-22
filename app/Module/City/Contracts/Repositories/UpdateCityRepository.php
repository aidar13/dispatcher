<?php

declare(strict_types=1);

namespace App\Module\City\Contracts\Repositories;

use App\Module\City\Models\City;

interface UpdateCityRepository
{
    public function update(City $city): void;
}
