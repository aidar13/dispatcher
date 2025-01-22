<?php

declare(strict_types=1);

namespace App\Module\Courier\Contracts\Repositories;

use App\Module\Courier\Models\CourierLicense;

interface CreateCourierLicenseRepository
{
    public function create(CourierLicense $model);
}
