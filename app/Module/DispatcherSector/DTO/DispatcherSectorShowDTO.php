<?php

declare(strict_types=1);

namespace App\Module\DispatcherSector\DTO;

use App\Module\DispatcherSector\Requests\DispatcherSectorShowRequest;
use App\Traits\ToArrayTrait;

final class DispatcherSectorShowDTO
{
    use ToArrayTrait;

    public int $limit;
    public int $page;
    public ?string $name;

    public static function fromRequest(DispatcherSectorShowRequest $request): self
    {
        $self        = new self();
        $self->name  = $request->get('name');
        $self->page  = (int)$request->get('page', 1);
        $self->limit = (int)$request->get('limit', 20);

        return $self;
    }
}
