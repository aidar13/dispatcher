<?php

declare(strict_types=1);

namespace App\Module\Car\Queries;

use App\Module\Car\Contracts\Queries\CarOccupancyTypeQuery as CarOccupancyTypeQueryContract;
use App\Module\Car\Models\CarOccupancyType;
use Illuminate\Database\Eloquent\Collection;

final class CarOccupancyTypeQuery implements CarOccupancyTypeQueryContract
{
    public function getAllCarOccupancyTypes(): Collection
    {
        /** @var Collection */
        return CarOccupancyType::all();
    }

    public function getByPercent(int $percent): CarOccupancyType
    {
        return CarOccupancyType::where('percent', $percent)->firstOrFail();
    }

    public function getVisibleTypes(): Collection
    {
        return CarOccupancyType::where('is_visible', true)->get();
    }
}
