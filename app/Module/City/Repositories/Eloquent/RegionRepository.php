<?php

declare(strict_types=1);

namespace App\Module\City\Repositories\Eloquent;

use App\Module\City\Contracts\Repositories\CreateRegionRepository;
use App\Module\City\Models\Region;

final class RegionRepository implements CreateRegionRepository
{
    public function create(Region $region): void
    {
        $region->save();
    }
}
