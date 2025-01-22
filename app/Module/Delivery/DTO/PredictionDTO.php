<?php

declare(strict_types=1);

namespace App\Module\Delivery\DTO;

use App\Module\Delivery\Requests\PredictionRequest;
use App\Module\DispatcherSector\Models\Wave;
use Illuminate\Support\Carbon;

final class PredictionDTO
{
    public int $dispatcherSectorId;
    public Carbon $date;

    public static function fromRequest(PredictionRequest $request): self
    {
        $self                     = new self();
        $self->dispatcherSectorId = (int)$request->get('dispatcherSectorId');
        $self->date               = $self->setDate($request->get('date'));

        return $self;
    }

    private function setDate(?string $date): Carbon
    {
        if ($date) {
            return Carbon::parse($date)->endOfDay();
        }

        return now()->hour >= Wave::NEXT_DAY_PLANNING_TIME
            ? now()->addDay()->endOfDay()
            : now()->endOfDay();
    }
}
