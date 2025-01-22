<?php

declare(strict_types=1);

namespace App\Module\Planning\DTO;

use App\Module\Planning\Requests\GenerateContainerRequest;

final class GenerateContainerDTO
{
    public int $dispatcherSectorId;
    public int $waveId;
    public string $date;
    public ?array $sectorIds;
    public ?int $statusId;

    public static function fromRequest(GenerateContainerRequest $request): self
    {
        $self                     = new self();
        $self->dispatcherSectorId = (int)$request->get('dispatcherSectorId');
        $self->waveId             = (int)$request->get('waveId');
        $self->statusId           = (int)$request->get('statusId') ?: null;
        $self->sectorIds          = $request->get('sectorIds');
        $self->date               = $request->get('date');

        return $self;
    }
}
