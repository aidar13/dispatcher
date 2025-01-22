<?php

declare(strict_types=1);

namespace App\Module\Take\Contracts\Services;

use App\Module\Order\Models\Order;
use App\Module\Take\DTO\OrderTakeShowDTO;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface OrderTakeService
{
    public function getAllPaginated(OrderTakeShowDTO $DTO): LengthAwarePaginator;

    public function getOrderWithTakes(int $orderId): Order;
}
