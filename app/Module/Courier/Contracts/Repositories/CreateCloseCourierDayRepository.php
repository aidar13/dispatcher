<?php

declare(strict_types=1);

namespace App\Module\Courier\Contracts\Repositories;

use App\Module\Courier\Models\CloseCourierDay;

interface CreateCloseCourierDayRepository
{
    public function create(CloseCourierDay $model);
}
