<?php

declare(strict_types=1);

namespace App\Module\CourierApp\DTO\CourierState;

use App\Module\CourierApp\Requests\CourierState\CreateCourierStateRequest;
use App\Module\Take\Models\OrderTake;

final class CreateOrderTakeCourierStateDTO
{
    public int $clientId;
    public string $clientType;
    public ?string $latitude;
    public ?string $longitude;

    public static function fromRequest(CreateCourierStateRequest $request): self
    {
        $self             = new self();
        $self->clientId   = (int)$request->get('clientId');
        $self->clientType = OrderTake::class;
        $self->latitude   = (string)$request->get('latitude') ?: null;
        $self->longitude  = (string)$request->get('longitude') ?: null;

        return $self;
    }
}
