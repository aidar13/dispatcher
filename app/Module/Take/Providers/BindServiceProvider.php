<?php

declare(strict_types=1);

namespace App\Module\Take\Providers;

use App\Module\Take\Contracts\Services\OrderTakeService as OrderTakeContract;
use App\Module\Take\Contracts\Services\OrderPeriodService as OrderPeriodServiceContract;
use App\Module\Take\Contracts\Services\OrderTakeReportService as OrderTakeReportServiceContract;
use App\Module\Take\Services\OrderPeriodService;
use App\Module\Take\Services\OrderTakeReportService;
use App\Module\Take\Services\OrderTakeService;
use Illuminate\Support\ServiceProvider;

class BindServiceProvider extends ServiceProvider
{
    public array $bindings = [
        OrderTakeContract::class              => OrderTakeService::class,
        OrderPeriodServiceContract::class     => OrderPeriodService::class,
        OrderTakeReportServiceContract::class => OrderTakeReportService::class,
    ];
}
