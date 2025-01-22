<?php

declare(strict_types=1);

namespace App\Module\Courier\Repositories\Eloquent;

use App\Module\Courier\Contracts\Repositories\CreateCloseCourierDayRepository;
use App\Module\Courier\Models\CloseCourierDay;

final class CloseCourierDayRepository implements CreateCloseCourierDayRepository
{
    /**
     * @throws \Throwable
     */
    public function create(CloseCourierDay $model)
    {
        $model->saveOrFail();
    }
}
