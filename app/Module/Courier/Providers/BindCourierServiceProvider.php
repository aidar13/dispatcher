<?php

declare(strict_types=1);

namespace App\Module\Courier\Providers;

use App\Module\Courier\Contracts\Services\CourierReportService as CourierEndOfDayServiceServiceContract;
use App\Module\Courier\Contracts\Services\CourierScheduleService as CourierScheduleServiceContract;
use App\Module\Courier\Contracts\Services\CourierScheduleTypeService as CourierScheduleTypeServiceContract;
use App\Module\Courier\Contracts\Services\CourierService as CourierServiceContract;
use App\Module\Courier\Services\CourierReportService;
use App\Module\Courier\Services\CourierScheduleService;
use App\Module\Courier\Services\CourierScheduleTypeService;
use App\Module\Courier\Services\CourierService;
use Illuminate\Support\ServiceProvider;

class BindCourierServiceProvider extends ServiceProvider
{
    public array $bindings = [
        // Services
        CourierServiceContract::class                => CourierService::class,
        CourierScheduleTypeServiceContract::class    => CourierScheduleTypeService::class,
        CourierEndOfDayServiceServiceContract::class => CourierReportService::class,
        CourierScheduleServiceContract::class        => CourierScheduleService::class,
    ];
}
