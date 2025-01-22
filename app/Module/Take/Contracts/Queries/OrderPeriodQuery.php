<?php

declare(strict_types=1);

namespace App\Module\Take\Contracts\Queries;

use App\Module\Take\DTO\OrderPeriodDTO;
use Illuminate\Database\Eloquent\Collection;

interface OrderPeriodQuery
{
    public function getAll(OrderPeriodDTO $DTO): Collection|array;
}
