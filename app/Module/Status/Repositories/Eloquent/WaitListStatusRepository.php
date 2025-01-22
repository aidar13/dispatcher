<?php

declare(strict_types=1);

namespace App\Module\Status\Repositories\Eloquent;

use App\Module\Status\Contracts\Repositories\CreateWaitListStatusRepository;
use App\Module\Status\Models\WaitListStatus;

final class WaitListStatusRepository implements CreateWaitListStatusRepository
{
    /**
     * @throws \Throwable
     */
    public function save(WaitListStatus $model): void
    {
        $model->saveOrFail();
    }
}
