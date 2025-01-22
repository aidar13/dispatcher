<?php

declare(strict_types=1);

namespace App\Module\Order\Contracts\Repositories\Integration;

use App\Module\Order\DTO\Integration\CreateFastDeliveryOrderDTO;
use App\Module\Order\DTO\Integration\FastDeliveryOrderDTO;

interface CreateFastDeliveryOrderRepository
{
    public function create(CreateFastDeliveryOrderDTO $DTO): FastDeliveryOrderDTO;
}
