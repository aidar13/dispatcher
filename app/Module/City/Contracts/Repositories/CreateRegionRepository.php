<?php

declare(strict_types=1);

namespace App\Module\City\Contracts\Repositories;

use App\Module\City\Models\Region;

interface CreateRegionRepository
{
    public function create(Region $region): void;
}
