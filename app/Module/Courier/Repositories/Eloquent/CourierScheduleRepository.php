<?php

declare(strict_types=1);

namespace App\Module\Courier\Repositories\Eloquent;

use App\Module\Courier\Contracts\Repositories\CreateCourierScheduleRepository;
use App\Module\Courier\Models\CourierSchedule;
use Throwable;

final class CourierScheduleRepository implements CreateCourierScheduleRepository
{
    /**
     * @throws Throwable
     */
    public function create(CourierSchedule $courierSchedule): void
    {
        $courierSchedule->saveOrFail();
    }
}
