<?php

declare(strict_types=1);

namespace App\Module\Take\Services;

use App\Module\Order\Contracts\Queries\OrderQuery;
use App\Module\Order\Models\Order;
use App\Module\Take\Contracts\Queries\OrderTakeQuery;
use App\Module\Take\Contracts\Services\OrderTakeService as OrderTakeServiceContract;
use App\Module\Take\DTO\OrderTakeShowDTO;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

final class OrderTakeService implements OrderTakeServiceContract
{
    public function __construct(
        private readonly OrderTakeQuery $query,
        private readonly OrderQuery $orderQuery,
    ) {
    }

    public function getAllPaginated(OrderTakeShowDTO $DTO): LengthAwarePaginator
    {
        return $this->query->getAllPaginated($DTO);
    }

    public function getOrderWithTakes(int $orderId): Order
    {
        return $this->orderQuery->getWithTakes($orderId);
    }
}
