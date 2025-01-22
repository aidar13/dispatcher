<?php

declare(strict_types=1);

namespace App\Module\Courier\DTO;

final class CourierScheduleInfoDTO
{
    public int $weekday;
    public string $workTimeFrom;
    public string $workTimeUntil;

    public static function fromArray(array $schedule): self
    {
        $self                = new self();
        $self->weekday       = (int)$schedule['weekday'];
        $self->workTimeFrom  = $schedule['workTimeFrom'];
        $self->workTimeUntil = $schedule['workTimeUntil'];

        return $self;
    }
}
