<?php

declare(strict_types=1);

namespace App\Module\DispatcherSector\Contracts\Queries;

use App\Module\DispatcherSector\Models\DispatcherSector;

interface DispatcherSectorPolygonQuery
{
    public function findByCoordinates(?string $latitude, ?string $longitude): ?DispatcherSector;
}
