<?php

declare(strict_types=1);

namespace App\Module\City\Contracts\Repositories;

use App\Module\City\Models\Country;

interface CreateCountryRepository
{
    public function create(Country $country): void;
}
