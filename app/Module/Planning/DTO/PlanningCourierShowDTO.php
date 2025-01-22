<?php

declare(strict_types=1);

namespace App\Module\Planning\DTO;

use App\Module\Planning\Requests\PlanningCourierRequest;

final class PlanningCourierShowDTO
{
    public int $dispatcherSectorId;
    public int $waveId;
    public string $date;

    public static function fromRequest(PlanningCourierRequest $request): self
    {
        $self                     = new self();
        $self->dispatcherSectorId = (int)$request->get('dispatcherSectorId');
        $self->waveId             = (int)$request->get('waveId');
        $self->date               = $request->get('date');

        return $self;
    }
}
