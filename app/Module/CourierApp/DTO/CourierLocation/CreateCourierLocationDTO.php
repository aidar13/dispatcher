<?php

declare(strict_types=1);

namespace App\Module\CourierApp\DTO\CourierLocation;

use App\Module\CourierApp\Requests\CourierLocation\CreateCourierLocationRequest;

final class CreateCourierLocationDTO
{
    public ?string $latitude;
    public ?string $longitude;
    public ?string $time;

    public static function fromRequest(CreateCourierLocationRequest $request): self
    {
        $self            = new self();
        $self->latitude  = (string)$request->get('latitude') ?: null;
        $self->longitude = (string)$request->get('longitude') ?: null;
        $self->time      = (string)$request->get('time') ?: null;

        return $self;
    }
}
