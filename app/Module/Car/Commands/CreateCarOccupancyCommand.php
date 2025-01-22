<?php

declare(strict_types=1);

namespace App\Module\Car\Commands;

use App\Module\Car\DTO\CarOccupancyDTO;

final class CreateCarOccupancyCommand
{
    public function __construct(public readonly CarOccupancyDTO $DTO)
    {
    }
}
