<?php

declare(strict_types=1);

namespace App\Module\Status\Contracts\Repositories;

use App\Module\Status\Models\OrderStatus;

interface CreateOrderStatusRepository
{
    public function create(OrderStatus $status): void;
}
