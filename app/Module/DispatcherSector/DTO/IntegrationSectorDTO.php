<?php

declare(strict_types=1);

namespace App\Module\DispatcherSector\DTO;

use App\Module\DispatcherSector\Models\Sector;

final class IntegrationSectorDTO
{
    public int $id;
    public string $name;
    public int $dispatcherSectorId;
    public ?array $coordinates;
    public string $color;

    public static function fromModel(Sector $sector): self
    {
        $self                     = new self();
        $self->id                 = $sector->id;
        $self->name               = $sector->name;
        $self->dispatcherSectorId = $sector->dispatcher_sector_id;
        $self->coordinates        = json_decode($sector->coordinates);
        $self->color              = $sector->color;

        return $self;
    }
}
