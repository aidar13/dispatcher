<?php

declare(strict_types=1);

namespace App\Module\Take\Contracts\Services;

use App\Module\Take\DTO\OrderPeriodDTO;
use Illuminate\Database\Eloquent\Collection;

interface OrderPeriodService
{
    public function getAll(OrderPeriodDTO $DTO): Collection|array;
}
