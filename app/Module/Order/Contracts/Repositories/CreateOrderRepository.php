<?php

declare(strict_types=1);

namespace App\Module\Order\Contracts\Repositories;

use App\Module\Order\Models\Order;

interface CreateOrderRepository
{
    public function create(Order $order): void;
}
