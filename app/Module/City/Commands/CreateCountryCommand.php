<?php

declare(strict_types=1);

namespace App\Module\City\Commands;

use App\Module\City\DTO\CountryDTO;

final class CreateCountryCommand
{
    public function __construct(public readonly CountryDTO $DTO)
    {
    }
}
