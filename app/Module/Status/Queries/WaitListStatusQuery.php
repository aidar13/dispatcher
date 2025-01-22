<?php

declare(strict_types=1);

namespace App\Module\Status\Queries;

use App\Module\Status\Contracts\Queries\WaitListStatusQuery as WaitListStatusQueryContract;
use App\Module\Status\Models\WaitListStatus;

final class WaitListStatusQuery implements WaitListStatusQueryContract
{
    public function getById(int $id): WaitListStatus
    {
        /** @var WaitListStatus */
        return WaitListStatus::query()
            ->findOrFail($id);
    }
}
