<?php

declare(strict_types=1);

namespace App\Module\Courier\Repositories\Eloquent;

use App\Module\Courier\Contracts\Repositories\UpdateCourierLicenseRepository;
use App\Module\Courier\Models\CourierLicense;
use App\Module\Courier\Contracts\Repositories\CreateCourierLicenseRepository;
use Throwable;

final class CourierLicenseRepository implements CreateCourierLicenseRepository, UpdateCourierLicenseRepository
{
    /**
     * @throws Throwable
     */
    public function create(CourierLicense $model): void
    {
        $model->saveOrFail();
    }

    /**
     * @throws Throwable
     */
    public function update(CourierLicense $model): void
    {
        $model->updateOrFail();
    }
}
