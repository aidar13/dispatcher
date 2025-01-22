<?php

declare(strict_types=1);

namespace App\Module\DispatcherSector\DTO;

use App\Module\DispatcherSector\Requests\CreateSectorRequest;
use App\Traits\ToArrayTrait;

final class CreateSectorDTO
{
    use ToArrayTrait;

    public string $name;
    public int $dispatcherSectorId;
    public ?array $coordinates;
    public string $color;

    public static function fromRequest(CreateSectorRequest $request): self
    {
        $self                     = new self();
        $self->name               = $request->get('name');
        $self->dispatcherSectorId = (int)$request->get('dispatcherSectorId');
        $self->coordinates        = $request->get('coordinates');
        $self->color              = $request->get('color');

        return $self;
    }
}
