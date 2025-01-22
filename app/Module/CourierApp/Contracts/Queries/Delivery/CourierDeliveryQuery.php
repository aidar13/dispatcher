<?php

declare(strict_types=1);

namespace App\Module\CourierApp\Contracts\Queries\Delivery;

use App\Module\CourierApp\DTO\Delivery\CourierDeliveryShowDTO;
use App\Module\Delivery\Models\Delivery;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

interface CourierDeliveryQuery
{
    public function getAllPaginated(CourierDeliveryShowDTO $DTO): LengthAwarePaginator;

    public function getById(int $id): Delivery;

    public function getAllByCourierId(int $courierId): Collection;
}
