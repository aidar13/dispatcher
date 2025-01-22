<?php

declare(strict_types=1);

namespace App\Module\DispatcherSector\DTO\Integration;

use Illuminate\Support\Carbon;

final class DispatcherSectorDTO
{
    public int $id;
    public ?int $cityId;
    public ?string $name;
    public ?string $description;
    public ?string $coordinates;
    public ?string $polygon;
    public ?array $userIds;
    public Carbon $createdAt;

    public static function fromEvent($event): self
    {
        $self              = new self();
        $self->id          = $event->DTO->id;
        $self->cityId      = $event->DTO->cityId;
        $self->name        = $event->DTO->name;
        $self->description = $event->DTO->description;
        $self->coordinates = $event->DTO->coordinates;
        $self->polygon     = $event->DTO->polygon;
        $self->userIds     = $event->DTO->userIds;
        $self->createdAt   = new Carbon($event->DTO->createdAt);

        return $self;
    }
}
