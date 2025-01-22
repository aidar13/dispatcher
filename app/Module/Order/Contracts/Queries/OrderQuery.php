<?php

declare(strict_types=1);

namespace App\Module\Order\Contracts\Queries;

use App\Module\Order\Models\Order;
use App\Module\Take\DTO\OrderTakeShowDTO;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface OrderQuery
{
    public function getById(int $id, array $columns = ['*'], array $relations = []): ?Order;

    public function getWithTakesAllPaginated(OrderTakeShowDTO $DTO): LengthAwarePaginator;

    public function getWithTakes(int $orderId): Order;
}
