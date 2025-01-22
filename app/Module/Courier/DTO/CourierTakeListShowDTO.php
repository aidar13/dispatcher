<?php

declare(strict_types=1);

namespace App\Module\Courier\DTO;

use App\Module\Courier\Requests\CourierTakeListShowRequest;
use App\Traits\ToArrayTrait;

final class CourierTakeListShowDTO
{
    use ToArrayTrait;

    public int $limit;
    public int $page;
    public ?int $dispatcherSectorId;
    public ?array $statusIds;
    public ?int $scheduleTypeId;
    public ?array $sectorIds;

    public static function fromRequest(CourierTakeListShowRequest $request): self
    {
        $self                     = new self();
        $self->page               = (int)$request->get('page', 1);
        $self->limit              = (int)$request->get('limit', 20);
        $self->statusIds          = $request->get('statusIds');
        $self->dispatcherSectorId = (int)$request->get('dispatcherSectorId') ?: null;
        $self->scheduleTypeId     = (int)$request->get('scheduleTypeId') ?: null;
        $self->sectorIds          = $request->get('sectorIds');

        return $self;
    }
}
