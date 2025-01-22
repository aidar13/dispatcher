<?php

declare(strict_types=1);

namespace App\Module\Courier\Contracts\Services;

use Illuminate\Database\Eloquent\Collection;

interface CourierScheduleTypeService
{
    public function getAllCourierScheduleTypes(): Collection;
}
