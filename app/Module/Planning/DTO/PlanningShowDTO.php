<?php

declare(strict_types=1);

namespace App\Module\Planning\DTO;

use App\Module\Planning\Requests\PlanningRequest;

final class PlanningShowDTO
{
    public int $dispatcherSectorId;
    public int $waveId;
    public string $date;
    public ?array $sectorIds;
    public ?int $statusId;
    public ?string $invoiceNumber;

    public static function fromRequest(PlanningRequest $request): self
    {
        $self                     = new self();
        $self->dispatcherSectorId = (int)$request->get('dispatcherSectorId');
        $self->waveId             = (int)$request->get('waveId');
        $self->date               = $request->get('date');
        $self->sectorIds          = $request->get('sectorIds');
        $self->statusId           = (int)$request->get('statusId') ?: null;
        $self->invoiceNumber      = $request->get('invoiceNumber');

        return $self;
    }
}
