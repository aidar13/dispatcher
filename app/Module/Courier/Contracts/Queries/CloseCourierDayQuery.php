<?php

declare(strict_types=1);

namespace App\Module\Courier\Contracts\Queries;

use App\Module\Courier\Models\CloseCourierDay;

interface CloseCourierDayQuery
{
    public function getById(int $id): CloseCourierDay;
}
