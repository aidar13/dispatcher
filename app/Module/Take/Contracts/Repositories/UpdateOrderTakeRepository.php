<?php

declare(strict_types=1);

namespace App\Module\Take\Contracts\Repositories;

use App\Module\Take\Models\OrderTake;

interface UpdateOrderTakeRepository
{
    public function update(OrderTake $take): void;
}
