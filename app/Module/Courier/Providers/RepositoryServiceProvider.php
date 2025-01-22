<?php

declare(strict_types=1);

namespace App\Module\Courier\Providers;

use App\Module\Courier\Contracts\Repositories\CreateCloseCourierDayRepository;
use App\Module\Courier\Contracts\Repositories\CreateCourierLicenseRepository;
use App\Module\Courier\Contracts\Repositories\CreateCourierPaymentRepository;
use App\Module\Courier\Contracts\Repositories\CreateCourierRepository;
use App\Module\Courier\Contracts\Repositories\CreateCourierScheduleRepository;
use App\Module\Courier\Contracts\Repositories\CreateCourierStopRepository;
use App\Module\Courier\Contracts\Repositories\UpdateCourierLicenseRepository;
use App\Module\Courier\Contracts\Repositories\UpdateCourierRepository;
use App\Module\Courier\Repositories\Eloquent\CloseCourierDayRepository;
use App\Module\Courier\Repositories\Eloquent\CourierLicenseRepository;
use App\Module\Courier\Repositories\Eloquent\CourierPaymentRepository;
use App\Module\Courier\Repositories\Eloquent\CourierRepository;
use App\Module\Courier\Repositories\Eloquent\CourierScheduleRepository;
use App\Module\Courier\Repositories\Eloquent\CourierStopRepository;
use Illuminate\Support\ServiceProvider;

final class RepositoryServiceProvider extends ServiceProvider
{
    public array $bindings = [
        CreateCourierRepository::class         => CourierRepository::class,
        UpdateCourierRepository::class         => CourierRepository::class,
        CreateCourierStopRepository::class     => CourierStopRepository::class,
        CreateCloseCourierDayRepository::class => CloseCourierDayRepository::class,
        CreateCourierScheduleRepository::class => CourierScheduleRepository::class,
        CreateCourierPaymentRepository::class  => CourierPaymentRepository::class,
        CreateCourierLicenseRepository::class  => CourierLicenseRepository::class,
        UpdateCourierLicenseRepository::class  => CourierLicenseRepository::class,
    ];
}
