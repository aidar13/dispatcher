<?php

declare(strict_types=1);

namespace App\Module\Courier\Repositories\Eloquent;

use App\Module\Courier\Contracts\Repositories\CreateCourierRepository;
use App\Module\Courier\Contracts\Repositories\UpdateCourierRepository;
use App\Module\Courier\Models\Courier;

final class CourierRepository implements CreateCourierRepository, UpdateCourierRepository
{
    /**
     * @throws \Throwable
     */
    public function create(Courier $courier): void
    {
        $courier->saveOrFail();
    }

    /**
     * @throws \Throwable
     */
    public function update(Courier $courier): void
    {
        $courier->saveOrFail();
    }
}
