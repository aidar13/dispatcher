<?php

declare(strict_types=1);

namespace App\Module\Status\Queries;

use App\Module\Status\Contracts\Queries\RefStatusQuery as RefStatusQueryContract;
use App\Module\Status\Models\RefStatus;

final class RefStatusQuery implements RefStatusQueryContract
{
    public function findByCode(int $code): RefStatus
    {
        /** @var RefStatus */
        return RefStatus::query()
            ->where('code', $code)
            ->firstOrFail();
    }
}
