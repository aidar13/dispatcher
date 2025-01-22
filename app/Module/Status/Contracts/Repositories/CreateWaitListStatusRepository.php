<?php

declare(strict_types=1);

namespace App\Module\Status\Contracts\Repositories;

use App\Module\Status\Models\WaitListStatus;

interface CreateWaitListStatusRepository
{
    public function save(WaitListStatus $model): void;
}
