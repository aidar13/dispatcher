<?php

declare(strict_types=1);

namespace App\Module\Status\Repositories\Eloquent;

use App\Module\Status\Contracts\Repositories\CreateOrderStatusRepository;
use App\Module\Status\Models\OrderStatus;

final class OrderStatusRepository implements CreateOrderStatusRepository
{
    public function create(OrderStatus $status): void
    {
        $status->save();
    }
}
