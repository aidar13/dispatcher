<?php

declare(strict_types=1);

namespace App\Module\CourierApp\Contracts\Repositories\CourierState;

use App\Module\CourierApp\Models\CourierState;

interface CreateCourierStateRepository
{
    public function create(CourierState $model): void;
}
