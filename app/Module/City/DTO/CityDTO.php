<?php

declare(strict_types=1);

namespace App\Module\City\DTO;

final class CityDTO
{
    public int $id;
    public string $name;
    public int $regionId;
    public int $typeId;
    public ?string $code;
    public ?float $longitude;
    public ?float $latitude;
    public ?string $coordinates;

    public static function fromEvent($event): self
    {
        $self              = new self();
        $self->id          = $event->DTO->id;
        $self->name        = $event->DTO->name;
        $self->regionId    = $event->DTO->regionId;
        $self->typeId      = $event->DTO->typeId;
        $self->code        = $event->DTO->code;
        $self->longitude   = $event->DTO->longitude;
        $self->latitude    = $event->DTO->latitude;
        $self->coordinates = $event->DTO->coordinates;

        return $self;
    }
}
