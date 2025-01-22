<?php

declare(strict_types=1);

namespace App\Module\DispatcherSector\DTO;

use App\Module\DispatcherSector\Requests\SectorShowRequest;
use App\Traits\ToArrayTrait;

final class SectorShowDTO
{
    use ToArrayTrait;

    public int $limit;
    public int $page;
    public ?string $name;
    public ?int $cityId;
    public ?array $dispatcherSectorIds;

    public static function fromRequest(SectorShowRequest $request): self
    {
        $self                      = new self();
        $self->name                = $request->get('name');
        $self->cityId              = (int)$request->get('cityId') ?: null;
        $self->dispatcherSectorIds = $request->get('dispatcherSectorIds');
        $self->page                = (int)$request->get('page', 1);
        $self->limit               = (int)$request->get('limit', 20);

        return $self;
    }
}
