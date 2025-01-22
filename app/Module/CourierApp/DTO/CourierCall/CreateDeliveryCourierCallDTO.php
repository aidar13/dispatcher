<?php

declare(strict_types=1);

namespace App\Module\CourierApp\DTO\CourierCall;

use App\Module\CourierApp\Requests\CourierCall\CreateCourierCallRequest;
use App\Module\Delivery\Models\Delivery;

final class CreateDeliveryCourierCallDTO
{
    public int $clientId;
    public string $clientType;
    public string $phone;

    public static function fromRequest(CreateCourierCallRequest $request): self
    {
        $self             = new self();
        $self->clientId   = $request->input('clientId');
        $self->clientType = Delivery::class;
        $self->phone      = $request->input('phone');
        return $self;
    }
}
