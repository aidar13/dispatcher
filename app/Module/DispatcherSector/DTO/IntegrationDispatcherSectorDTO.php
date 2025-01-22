<?php

declare(strict_types=1);

namespace App\Module\DispatcherSector\DTO;

use App\Module\DispatcherSector\Models\DispatcherSector;

final class IntegrationDispatcherSectorDTO
{
    public int $id;
    public string $name;
    public int $cityId;
    public ?int $deliveryManagerId;
    public string $description;
    public array $coordinates;
    public array $dispatcherIds = [];

    public static function fromModel(DispatcherSector $dispatcherSector): self
    {
        $self                    = new self();
        $self->id                = $dispatcherSector->id;
        $self->name              = $dispatcherSector->name;
        $self->cityId            = $dispatcherSector->city_id;
        $self->deliveryManagerId = $dispatcherSector->delivery_manager_id;
        $self->description       = $dispatcherSector->description;
        $self->coordinates       = json_decode($dispatcherSector->coordinates);

        return $self;
    }

    public function setDispatcherIds(array $dispatcherIds): void
    {
        $this->dispatcherIds = $dispatcherIds;
    }
}
