<?php

declare(strict_types=1);

namespace App\Module\Courier\Repositories\Eloquent;

use App\Module\Courier\Contracts\Repositories\CreateCourierStopRepository;
use App\Module\CourierApp\Models\CourierStop;

final class CourierStopRepository implements CreateCourierStopRepository
{
    public function save(CourierStop $model): void
    {
        $model->save();
    }
}
