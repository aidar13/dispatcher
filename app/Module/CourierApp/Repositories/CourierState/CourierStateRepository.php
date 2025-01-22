<?php

declare(strict_types=1);

namespace App\Module\CourierApp\Repositories\CourierState;

use App\Module\CourierApp\Contracts\Repositories\CourierState\CreateCourierStateRepository;
use App\Module\CourierApp\Models\CourierState;
use Throwable;

final class CourierStateRepository implements CreateCourierStateRepository
{
    /**
     * @throws Throwable
     */
    public function create(CourierState $model): void
    {
        $model->saveOrFail();
    }
}
