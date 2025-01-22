<?php

declare(strict_types=1);

namespace App\Module\City\Queries;

use App\Module\City\Contracts\Queries\RegionQuery as RegionQueryContract;
use App\Module\City\Models\Region;

final class RegionQuery implements RegionQueryContract
{
    public function getById(int $id): Region
    {
        return Region::findOrFail($id);
    }
}
