<?php

declare(strict_types=1);

namespace App\Module\Courier\Services;

use App\Module\Courier\Contracts\Queries\CourierScheduleTypeQuery;
use App\Module\Courier\Contracts\Services\CourierScheduleTypeService as CourierScheduleTypeServiceContract;
use Illuminate\Database\Eloquent\Collection;

final class CourierScheduleTypeService implements CourierScheduleTypeServiceContract
{
    public function __construct(
        private readonly CourierScheduleTypeQuery $query
    ) {
    }

    public function getAllCourierScheduleTypes(): Collection
    {
        return $this->query->getAllCourierScheduleTypes();
    }
}
