<?php

declare(strict_types=1);

namespace App\Module\CourierApp\Contracts\Repositories\CourierLocation;

use App\Module\CourierApp\Models\CourierLoaction;

interface CreateCourierLocationRepository
{
    public function create(CourierLoaction $model): void;
}
