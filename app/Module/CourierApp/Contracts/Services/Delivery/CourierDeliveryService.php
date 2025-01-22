<?php

declare(strict_types=1);

namespace App\Module\CourierApp\Contracts\Services\Delivery;

use App\Module\CourierApp\DTO\Delivery\CourierDeliveryShowDTO;
use App\Module\Delivery\Models\Delivery;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface CourierDeliveryService
{
    public function getAllPaginated(CourierDeliveryShowDTO $DTO): LengthAwarePaginator;

    public function getById(int $id): Delivery;
}
