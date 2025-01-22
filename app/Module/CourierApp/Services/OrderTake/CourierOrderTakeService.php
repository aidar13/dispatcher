<?php

declare(strict_types=1);

namespace App\Module\CourierApp\Services\OrderTake;

use App\Module\CourierApp\Contracts\Queries\OrderTake\CourierOrderTakeQuery;
use App\Module\CourierApp\Contracts\Services\OrderTake\CourierOrderTakeService as CourierOrderTakeServiceContract;
use App\Module\CourierApp\DTO\OrderTake\CourierOrderTakeShowDTO;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

final class CourierOrderTakeService implements CourierOrderTakeServiceContract
{
    public function __construct(
        private readonly CourierOrderTakeQuery $query,
    ) {
    }

    public function getAllPaginated(CourierOrderTakeShowDTO $DTO): LengthAwarePaginator
    {
        return $this->query->getAllPaginated($DTO);
    }

    public function getAllByOrderId(int $orderId): Collection|array
    {
        return $this->query->getAllByOrderId($orderId);
    }
}
