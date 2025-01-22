<?php

declare(strict_types=1);

namespace App\Module\City\Commands;

use App\Module\City\DTO\CityDTO;

final class CreateCityCommand
{
    public function __construct(public readonly CityDTO $DTO)
    {
    }
}
