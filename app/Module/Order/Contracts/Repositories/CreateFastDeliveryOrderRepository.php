<?php

declare(strict_types=1);

namespace App\Module\Order\Contracts\Repositories;

use App\Module\Order\Models\FastDeliveryOrder;

interface CreateFastDeliveryOrderRepository
{
    public function create(FastDeliveryOrder $order): void;
}
