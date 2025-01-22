<?php

declare(strict_types=1);

namespace App\Module\DispatcherSector\DTO;

use App\Module\DispatcherSector\Requests\UpdateDispatcherSectorRequest;
use App\Traits\ToArrayTrait;

final class UpdateDispatcherSectorDTO
{
    use ToArrayTrait;

    public string $description;
    public array $coordinates;
    public string $name;
    public int $cityId;
    public ?int $deliveryManagerId;
    public array $dispatcherIds;

    public static function fromRequest(UpdateDispatcherSectorRequest $request): self
    {
        $self                    = new self();
        $self->coordinates       = $request->get('coordinates');
        $self->name              = $request->get('name');
        $self->description       = $request->get('description');
        $self->cityId            = (int)$request->get('cityId');
        $self->deliveryManagerId = (int)$request->get('deliveryManagerId') ?: null;
        $self->dispatcherIds     = $request->get('dispatcherIds', []);

        return $self;
    }
}
