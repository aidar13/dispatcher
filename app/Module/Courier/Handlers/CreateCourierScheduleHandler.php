<?php

declare(strict_types=1);

namespace App\Module\Courier\Handlers;

use App\Module\Courier\Commands\CreateCourierScheduleCommand;
use App\Module\Courier\Contracts\Repositories\CreateCourierScheduleRepository;
use App\Module\Courier\Models\CourierSchedule;

final class CreateCourierScheduleHandler
{
    public function __construct(
        private readonly CreateCourierScheduleRepository $repository
    ) {
    }

    public function handle(CreateCourierScheduleCommand $command): void
    {
        foreach ($command->DTO->schedules as $schedule) {
            $courierSchedule                  = new CourierSchedule();
            $courierSchedule->courier_id      = $command->DTO->courierId;
            $courierSchedule->weekday         = $schedule->weekday;
            $courierSchedule->work_time_from  = $schedule->workTimeFrom;
            $courierSchedule->work_time_until = $schedule->workTimeUntil;

            $this->repository->create($courierSchedule);
        }
    }
}
