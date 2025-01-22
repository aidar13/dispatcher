<?php

declare(strict_types=1);

namespace App\Module\City\Contracts\Queries;

use App\Module\City\Models\Region;

interface RegionQuery
{
    public function getById(int $id): Region;
}
