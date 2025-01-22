<?php

declare(strict_types=1);

namespace App\Module\Car\Contracts\Queries;

use App\Module\Car\Models\CarOccupancyType;
use Illuminate\Database\Eloquent\Collection;

interface CarOccupancyTypeQuery
{
    public function getAllCarOccupancyTypes(): Collection;

    public function getByPercent(int $percent): CarOccupancyType;

    public function getVisibleTypes(): Collection;
}
