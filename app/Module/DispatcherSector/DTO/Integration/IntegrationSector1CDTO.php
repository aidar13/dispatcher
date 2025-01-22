<?php

declare(strict_types=1);

namespace App\Module\DispatcherSector\DTO\Integration;

use App\Module\DispatcherSector\Models\Sector;

final class IntegrationSector1CDTO
{
    public int $id;
    public string $name;
    public int $cityId;

    public static function fromModel(Sector $sector): self
    {
        $self         = new self();
        $self->id     = $sector->id;
        $self->name   = $sector->name;
        $self->cityId = $sector->dispatcherSector->city_id;

        return $self;
    }
}
