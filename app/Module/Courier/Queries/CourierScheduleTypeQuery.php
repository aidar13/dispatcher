<?php

declare(strict_types=1);

namespace App\Module\Courier\Queries;

use App\Module\Courier\Contracts\Queries\CourierScheduleTypeQuery as CourierScheduleTypeQueryContract;
use App\Module\Courier\Models\CourierScheduleType;
use Illuminate\Database\Eloquent\Collection;

final class CourierScheduleTypeQuery implements CourierScheduleTypeQueryContract
{
    public function getAllCourierScheduleTypes(): Collection
    {
        return CourierScheduleType::all();
    }
}
