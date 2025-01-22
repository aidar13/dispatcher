<?php

declare(strict_types=1);

namespace App\Module\Monitoring\DTO;

use App\Helpers\DateHelper;
use App\Module\Monitoring\Requests\TakeInfoShowRequest;
use App\Traits\ToArrayTrait;

final class TakeInfoShowDTO
{
    use ToArrayTrait;

    public ?int $dispatcherSectorId;
    public ?string $createdAtFrom;
    public ?string $createdAtTo;
    public ?string $takeDateFrom;
    public ?string $takeDateTo;

    public static function fromRequest(TakeInfoShowRequest $request): self
    {
        $self                     = new self();
        $self->dispatcherSectorId = (int)$request->get('dispatcherSectorId') ?: null;
        $self->createdAtFrom      = $request->get('createdAtFrom');
        $self->createdAtTo        = $request->get('createdAtTo');
        $self->takeDateFrom       = $request->get('takeDateFrom');
        $self->takeDateTo         = $request->get('takeDateTo');

        return $self;
    }
}
