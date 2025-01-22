<?php

declare(strict_types=1);

namespace App\Module\Order\Contracts\Services;

use App\Module\Order\Models\Order;
use Illuminate\Support\Collection;

interface OrderService
{
    public function getById(int $orderId, array $columns = ['*'], array $relations = []): ?Order;

    public function getProblems(Order $order): Collection;
}
