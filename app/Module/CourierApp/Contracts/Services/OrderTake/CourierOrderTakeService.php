<?php

declare(strict_types=1);

namespace App\Module\CourierApp\Contracts\Services\OrderTake;

use App\Module\CourierApp\DTO\OrderTake\CourierOrderTakeShowDTO;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

interface CourierOrderTakeService
{
    public function getAllPaginated(CourierOrderTakeShowDTO $DTO): LengthAwarePaginator;

    public function getAllByOrderId(int $orderId): Collection|array;
}
