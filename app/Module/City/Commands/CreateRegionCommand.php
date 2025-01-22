<?php

declare(strict_types=1);

namespace App\Module\City\Commands;

use App\Module\City\DTO\RegionDTO;

final class CreateRegionCommand
{
    public function __construct(public readonly RegionDTO $DTO)
    {
    }
}
