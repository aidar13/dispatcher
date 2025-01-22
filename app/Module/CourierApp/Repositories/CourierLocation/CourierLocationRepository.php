<?php

declare(strict_types=1);

namespace App\Module\CourierApp\Repositories\CourierLocation;

use App\Module\CourierApp\Contracts\Repositories\CourierLocation\CreateCourierLocationRepository;
use App\Module\CourierApp\Models\CourierLoaction;
use Throwable;

final class CourierLocationRepository implements CreateCourierLocationRepository
{
    /**
     * @throws Throwable
     */
    public function create(CourierLoaction $model): void
    {
        $model->saveOrFail();
    }
}
