<?php

declare(strict_types=1);

namespace App\Module\Car\Contracts\Repositories;

use App\Module\Car\Models\CarOccupancy;

interface CreateCarOccupancyRepository
{
    public function create(CarOccupancy $carOccupancy): void;
}
