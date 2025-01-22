<?php

declare(strict_types=1);

namespace App\Module\CourierApp\DTO\CourierState;

use App\Module\CourierApp\Requests\CourierState\CreateCourierStateRequest;
use App\Module\Delivery\Models\Delivery;

final class CreateDeliveryCourierStateDTO
{
    public int $clientId;
    public string $clientType;
    public ?string $latitude;
    public ?string $longitude;

    public static function fromRequest(CreateCourierStateRequest $request): self
    {
        $self             = new self();
        $self->clientId   = (int)$request->get('clientId');
        $self->clientType = Delivery::class;
        $self->latitude   = (string)$request->get('latitude') ?: null;
        $self->longitude  = (string)$request->get('longitude') ?: null;

        return $self;
    }
}
