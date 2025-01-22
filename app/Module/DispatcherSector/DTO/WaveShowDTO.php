<?php

declare(strict_types=1);

namespace App\Module\DispatcherSector\DTO;

use App\Module\DispatcherSector\Models\Wave;
use App\Module\DispatcherSector\Requests\WaveShowRequest;
use Illuminate\Support\Carbon;

final class WaveShowDTO
{
    public int $dispatcherSectorId;
    public ?int $sectorId;
    public ?array $additionalServices;
    public ?int $statusId;
    public ?int $waveId;
    public ?Carbon $date;

    public static function fromRequest(WaveShowRequest $request): self
    {
        $self                     = new self();
        $self->dispatcherSectorId = (int)$request->get('dispatcherSectorId');
        $self->sectorId           = (int)$request->get('sectorId') ?: null;
        $self->statusId           = (int)$request->get('statusId') ?: null;
        $self->additionalServices = $request->get('additionalServices');

        return $self;
    }

    public function setWaveId(int $waveId): void
    {
        $this->waveId = $waveId;
    }

    public function setDate(string $time): void
    {
        $date = Carbon::parse($time);

        $this->date = now()->hour >= Wave::NEXT_DAY_PLANNING_TIME
            ? $date->addDay()
            : $date;
    }
}
