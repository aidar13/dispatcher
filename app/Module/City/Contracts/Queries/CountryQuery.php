<?php

declare(strict_types=1);

namespace App\Module\City\Contracts\Queries;

use App\Module\City\Models\Country;

interface CountryQuery
{
    public function getById(int $id): Country;
}
