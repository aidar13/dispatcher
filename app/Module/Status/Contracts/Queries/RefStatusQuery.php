<?php

declare(strict_types=1);

namespace App\Module\Status\Contracts\Queries;

use App\Module\Status\Models\RefStatus;

interface RefStatusQuery
{
    public function findByCode(int $code): RefStatus;
}
