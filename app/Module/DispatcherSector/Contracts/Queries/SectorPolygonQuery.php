<?php

declare(strict_types=1);

namespace App\Module\DispatcherSector\Contracts\Queries;

use App\Module\DispatcherSector\Models\Sector;

interface SectorPolygonQuery
{
    public function findByCoordinates(?string $latitude, ?string $longitude): ?Sector;
}
