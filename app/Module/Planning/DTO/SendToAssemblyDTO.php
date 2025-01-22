<?php

declare(strict_types=1);

namespace App\Module\Planning\DTO;

use App\Module\Planning\Requests\SendToAssemblyRequest;

final class SendToAssemblyDTO
{
    public ?int $waveId;
    public ?string $date;
    public ?array $sectorIds;
    public ?array $containerIds;

    public static function fromRequest(SendToAssemblyRequest $request): self
    {
        $self               = new self();
        $self->waveId       = (int)$request->get('waveId') ?: null;
        $self->sectorIds    = $request->get('sectorIds');
        $self->date         = $request->get('date');
        $self->containerIds = $request->get('containerIds', []);

        return $self;
    }
}
