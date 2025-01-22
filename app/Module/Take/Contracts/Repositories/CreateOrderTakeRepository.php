<?php

declare(strict_types=1);

namespace App\Module\Take\Contracts\Repositories;

use App\Module\Take\Models\OrderTake;

interface CreateOrderTakeRepository
{
    public function create(OrderTake $take): void;
}
