<?php

declare(strict_types=1);

namespace App\Module\CourierApp\DTO\CarOccupancy;

use App\Module\Car\Models\CarOccupancy;
use App\Module\CourierApp\Requests\CarOccupancy\CreateCarOccupancyRequest;
use App\Module\Order\Models\Invoice;

final class DeliveryCarOccupancyDTO
{
    public int $carOccupancyTypeId;
    public int $typeId;
    public int $clientId;
    public string $clientType;

    public static function fromRequest(CreateCarOccupancyRequest $request): self
    {
        $self                     = new self();
        $self->carOccupancyTypeId = (int)$request->get('carOccupancyTypeId');
        $self->typeId             = CarOccupancy::COURIER_WORK_TYPE_ID_DELIVERY;
        $self->clientId           = (int)$request->get('clientId');
        $self->clientType         = Invoice::class;

        return $self;
    }

    public function setCarOccupancyTypeId(int $carOccupancyTypeId): void
    {
        $this->carOccupancyTypeId = $carOccupancyTypeId;
    }

    public function setTypeId(): void
    {
        $this->typeId = CarOccupancy::COURIER_WORK_TYPE_ID_DELIVERY;
    }

    public function setClientId(int $clientId): void
    {
        $this->clientId = $clientId;
    }

    public function setClientType(): void
    {
        $this->clientType = Invoice::class;
    }
}
