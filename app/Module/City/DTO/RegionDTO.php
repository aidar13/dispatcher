<?php

declare(strict_types=1);

namespace App\Module\City\DTO;

final class RegionDTO
{
    public int $id;
    public string $name;
    public int $countryId;

    public static function fromEvent($event): self
    {
        $self            = new self();
        $self->id        = $event->DTO->id;
        $self->name      = $event->DTO->name;
        $self->countryId = $event->DTO->countryId;

        return $self;
    }
}
