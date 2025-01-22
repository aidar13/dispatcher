<?php

declare(strict_types=1);

namespace App\Module\City\Queries;

use App\Module\City\Contracts\Queries\CityQuery as CityQueryContract;
use App\Module\City\Models\City;

final class CityQuery implements CityQueryContract
{
    public function getById(int $id): City
    {
        return City::findOrFail($id);
    }
}
