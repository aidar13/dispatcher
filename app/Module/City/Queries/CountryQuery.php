<?php

declare(strict_types=1);

namespace App\Module\City\Queries;

use App\Module\City\Contracts\Queries\CountryQuery as CountryQueryContract;
use App\Module\City\Models\Country;

final class CountryQuery implements CountryQueryContract
{
    public function getById(int $id): Country
    {
        return Country::findOrFail($id);
    }
}
