<?php

declare(strict_types=1);

namespace App\Module\Courier\Queries;

use App\Module\Courier\Contracts\Queries\CloseCourierDayQuery as CloseCourierDayQueryContract;
use App\Module\Courier\Models\CloseCourierDay;

final class CloseCourierDayQuery implements CloseCourierDayQueryContract
{
    public function getById(int $id): CloseCourierDay
    {
        return CloseCourierDay::findOrFail($id);
    }
}
