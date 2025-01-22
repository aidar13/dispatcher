<?php

declare(strict_types=1);

namespace App\Module\Status\DTO;

use App\Module\Status\Requests\StatusTypeIndexRequest;
use App\Traits\ToArrayTrait;

final class StatusTypeIndexDTO
{
    use ToArrayTrait;

    public int $limit;
    public int $page;
    public ?int $typeId;

    public static function fromRequest(StatusTypeIndexRequest $request): self
    {
        $self         = new self();
        $self->page   = (int)$request->get('page', 1);
        $self->limit  = (int)$request->get('limit', 20);
        $self->typeId = (int)$request->get('typeId') ?: null;

        return $self;
    }
}
