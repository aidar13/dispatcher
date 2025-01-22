<?php

declare(strict_types=1);

namespace App\Module\Courier\DTO;

use App\Module\Courier\Requests\CreateCourierScheduleRequest;
use Illuminate\Support\Collection;

final class CreateCourierScheduleDTO
{
    public int $courierId;
    public Collection $schedules;

    public static function fromRequest(CreateCourierScheduleRequest $request): self
    {
        $self            = new self();
        $self->courierId = (int)$request->input('courierId');
        $self->schedules = CourierScheduleCollectionDTO::fromArray($request->input('schedules'));

        return $self;
    }
}
