<?php

declare(strict_types=1);

namespace App\Module\City\Contracts\Queries;

use App\Module\City\Models\City;

interface CityQuery
{
    public function getById(int $id): City;
}
