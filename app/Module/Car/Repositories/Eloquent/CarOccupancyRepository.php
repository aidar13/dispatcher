<?php

declare(strict_types=1);

namespace App\Module\Car\Repositories\Eloquent;

use App\Module\Car\Contracts\Repositories\CreateCarOccupancyRepository;
use App\Module\Car\Models\CarOccupancy;
use Throwable;

final class CarOccupancyRepository implements CreateCarOccupancyRepository
{
    /**
     * @throws Throwable
     */
    public function create(CarOccupancy $carOccupancy): void
    {
        $carOccupancy->saveOrFail();
    }
}
