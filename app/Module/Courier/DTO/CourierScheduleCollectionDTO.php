<?php

declare(strict_types=1);

namespace App\Module\Courier\DTO;

use Illuminate\Support\Collection;

final class CourierScheduleCollectionDTO
{
    /**
     * @psalm-suppress InvalidArgument
     * @param array $schedules
     * @return Collection
     */
    public static function fromArray(array $schedules): Collection
    {
        $scheduleCollection = collect();

        foreach ($schedules as $schedule) {
            $scheduleCollection->push(CourierScheduleInfoDTO::fromArray($schedule));
        }

        return $scheduleCollection;
    }
}
