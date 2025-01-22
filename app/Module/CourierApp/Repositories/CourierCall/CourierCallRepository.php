<?php

declare(strict_types=1);

namespace App\Module\CourierApp\Repositories\CourierCall;

use App\Module\CourierApp\Contracts\Repositories\CourierCall\CreateCourierCallRepository;
use App\Module\CourierApp\Models\CourierCall;

class CourierCallRepository implements CreateCourierCallRepository
{
    public function create(CourierCall $courierCall): void
    {
        $courierCall->saveOrFail();
    }
}
