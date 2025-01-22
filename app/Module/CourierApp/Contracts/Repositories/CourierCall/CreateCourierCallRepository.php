<?php

declare(strict_types=1);

namespace App\Module\CourierApp\Contracts\Repositories\CourierCall;

use App\Module\CourierApp\Models\CourierCall;

interface CreateCourierCallRepository
{
    public function create(CourierCall $courierCall): void;
}
