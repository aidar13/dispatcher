<?php

declare(strict_types=1);

namespace Tests\Fake\Repositories;

use App\Module\Order\Contracts\Repositories\Integration\CreateFastDeliveryOrderRepository;
use App\Module\Order\DTO\Integration\CreateFastDeliveryOrderDTO;
use App\Module\Order\DTO\Integration\FastDeliveryOrderDTO;

final class FastDeliveryOrderFakerRepository implements CreateFastDeliveryOrderRepository
{
    public function __construct(private readonly ?int $deliveryId = null)
    {
    }

    public function create(CreateFastDeliveryOrderDTO $DTO): FastDeliveryOrderDTO
    {
        $dto                  = new FastDeliveryOrderDTO();
        $dto->internalOrderId = $this->deliveryId;

        return $dto;
    }
}
