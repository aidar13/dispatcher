<?php

declare(strict_types=1);

namespace App\Module\Courier\Contracts\Queries;

use App\Module\CourierApp\Models\CourierStop;

interface CourierStopQuery
{
    public function getById(int $id): ?CourierStop;
}
