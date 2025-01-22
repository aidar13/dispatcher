<?php

declare(strict_types=1);

namespace App\Module\DispatcherSector\DTO;

use App\Module\DispatcherSector\Requests\WaveRequest;
use App\Traits\ToArrayTrait;

final class WaveDTO
{
    use ToArrayTrait;

    public string $title;
    public int $dispatcherSectorId;
    public string $fromTime;
    public string $toTime;

    public static function fromRequest(WaveRequest $request): self
    {
        $self                     = new self();
        $self->title              = $request->get('title');
        $self->dispatcherSectorId = (int)$request->get('dispatcherSectorId');
        $self->fromTime           = $request->get('fromTime');
        $self->toTime             = $request->get('toTime');

        return $self;
    }
}
