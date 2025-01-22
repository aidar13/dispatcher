<?php

declare(strict_types=1);

namespace App\Module\CourierApp\Contracts\Queries\OrderTake;

use App\Module\CourierApp\DTO\OrderTake\CourierOrderTakeShowDTO;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

interface CourierOrderTakeQuery
{
    public function getAllPaginated(CourierOrderTakeShowDTO $DTO): LengthAwarePaginator;

    public function getAllByCourierId(int $courierId): Collection;

    public function getAllByOrderId(int $orderId): Collection|array;
}
