<?php

declare(strict_types=1);

namespace App\Module\City\Repositories\Eloquent;

use App\Module\City\Contracts\Repositories\CreateCountryRepository;
use App\Module\City\Models\Country;

final class CountryRepository implements CreateCountryRepository
{
    public function create(Country $country): void
    {
        $country->save();
    }
}
