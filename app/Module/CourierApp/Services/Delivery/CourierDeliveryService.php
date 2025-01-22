<?php

declare(strict_types=1);

namespace App\Module\CourierApp\Services\Delivery;

use App\Module\CourierApp\Contracts\Queries\Delivery\CourierDeliveryQuery;
use App\Module\CourierApp\Contracts\Services\Delivery\CourierDeliveryService as CourierDeliveryServiceContract;
use App\Module\CourierApp\DTO\Delivery\CourierDeliveryShowDTO;
use App\Module\Delivery\Models\Delivery;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

final class CourierDeliveryService implements CourierDeliveryServiceContract
{
    public function __construct(
        private readonly CourierDeliveryQuery $query
    ) {
    }

    public function getAllPaginated(CourierDeliveryShowDTO $DTO): LengthAwarePaginator
    {
        return $this->query->getAllPaginated($DTO);
    }

    public function getById(int $id): Delivery
    {
        return $this->query->getById($id);
    }
}
