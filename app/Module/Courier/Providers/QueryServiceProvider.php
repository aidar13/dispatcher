<?php

declare(strict_types=1);

namespace App\Module\Courier\Providers;

use App\Module\Courier\Contracts\Queries\CloseCourierDayQuery as CloseCourierDayQueryContract;
use App\Module\Courier\Contracts\Queries\CourierQuery as CourierQueryContract;
use App\Module\Courier\Contracts\Queries\CourierReportQuery as CourierEndOfDayQueryContract;
use App\Module\Courier\Contracts\Queries\CourierScheduleQuery as CourierScheduleQueryContract;
use App\Module\Courier\Contracts\Queries\CourierScheduleTypeQuery as CourierScheduleTypeQueryContract;
use App\Module\Courier\Contracts\Queries\CourierStopQuery as CourierStopQueryContract;
use App\Module\Courier\Queries\CloseCourierDayQuery;
use App\Module\Courier\Queries\CourierQuery;
use App\Module\Courier\Queries\CourierReportQuery;
use App\Module\Courier\Queries\CourierScheduleQuery;
use App\Module\Courier\Queries\CourierScheduleTypeQuery;
use App\Module\Courier\Queries\CourierStopQuery;
use Illuminate\Support\ServiceProvider;

final class QueryServiceProvider extends ServiceProvider
{
    public array $bindings = [
        CourierQueryContract::class             => CourierQuery::class,
        CourierScheduleTypeQueryContract::class => CourierScheduleTypeQuery::class,
        CourierEndOfDayQueryContract::class     => CourierReportQuery::class,
        CourierScheduleQueryContract::class     => CourierScheduleQuery::class,
        CloseCourierDayQueryContract::class     => CloseCourierDayQuery::class,
        CourierStopQueryContract::class         => CourierStopQuery::class,
    ];
}
