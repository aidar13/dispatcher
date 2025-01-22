<?php

declare(strict_types=1);

namespace App\Module\Car\DTO;

use Carbon\Carbon;

final class CarOccupancyDTO
{
    public int $id;
    public int $carOccupancyTypeId;
    public ?int $carId;
    public ?int $userId;
    public ?int $courierWorkTypeId;
    public int $clientId;
    public string $clientType;
    public ?Carbon $createdAt;

    public static function fromEvent($event): CarOccupancyDTO
    {
        $self = new self();

        $self->id                 = $event->id;
        $self->carOccupancyTypeId = $event->carOccupancyTypeId;
        $self->carId              = $event->carId;
        $self->userId             = $event->userId;
        $self->courierWorkTypeId  = $event->courierWorkTypeId;
        $self->clientId           = $event->occupanciableId;
        $self->clientType         = $event->occupanciableType;
        $self->createdAt          = new Carbon($event->createdAt) ?? null;

        return $self;
    }
}
