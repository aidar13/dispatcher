<?php

declare(strict_types=1);

namespace App\Module\CourierApp\DTO\CarOccupancy;

use App\Module\Car\Models\CarOccupancy;
use App\Module\CourierApp\Requests\CarOccupancy\CreateCarOccupancyRequest;
use App\Module\Order\Models\Order;

final class OrderTakeCarOccupancyDTO
{
    public int $carOccupancyTypeId;
    public int $typeId;
    public int $clientId;
    public string $clientType;

    public static function fromRequest(CreateCarOccupancyRequest $request): self
    {
        $self                     = new self();
        $self->carOccupancyTypeId = (int)$request->get('carOccupancyTypeId');
        $self->typeId             = CarOccupancy::COURIER_WORK_TYPE_ID_TAKE;
        $self->clientId           = (int)$request->get('clientId');
        $self->clientType         = Order::class;

        return $self;
    }
}
