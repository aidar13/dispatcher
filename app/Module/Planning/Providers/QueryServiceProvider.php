<?php

declare(strict_types=1);

namespace App\Module\Planning\Providers;

use App\Module\Planning\Contracts\Queries\PlanningQuery as PlanningQueryContract;
use App\Module\Planning\Contracts\Queries\ContainerQuery as ContainerQueryContract;
use App\Module\Planning\Contracts\Queries\ContainerInvoiceQuery as ContainerInvoiceQueryContract;
use App\Module\Planning\Queries\ContainerInvoiceQuery;
use App\Module\Planning\Queries\ContainerQuery;
use App\Module\Planning\Queries\PlanningQuery;
use Illuminate\Support\ServiceProvider;

final class QueryServiceProvider extends ServiceProvider
{
    public array $bindings = [
        PlanningQueryContract::class         => PlanningQuery::class,
        ContainerQueryContract::class        => ContainerQuery::class,
        ContainerInvoiceQueryContract::class => ContainerInvoiceQuery::class,
    ];
}
