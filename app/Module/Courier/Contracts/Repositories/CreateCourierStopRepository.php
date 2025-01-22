<?php

declare(strict_types=1);

namespace App\Module\Courier\Contracts\Repositories;

use App\Module\CourierApp\Models\CourierStop;

interface CreateCourierStopRepository
{
    public function save(CourierStop $model): void;
}
