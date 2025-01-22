<?php

declare(strict_types=1);

namespace App\Module\Status\Contracts\Queries;

use App\Module\Status\Models\WaitListStatus;

interface WaitListStatusQuery
{
    public function getById(int $id): WaitListStatus;
}
