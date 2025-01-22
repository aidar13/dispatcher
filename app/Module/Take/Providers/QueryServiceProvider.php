<?php

declare(strict_types=1);

namespace App\Module\Take\Providers;

use App\Module\Take\Contracts\Queries\CustomerQuery as CustomerQueryContract;
use App\Module\Take\Contracts\Queries\OrderTakeQuery as OrderTakeQueryContract;
use App\Module\Take\Queries\CustomerQuery;
use App\Module\Take\Queries\OrderTakeQuery;
use Illuminate\Support\ServiceProvider;

final class QueryServiceProvider extends ServiceProvider
{
    public array $bindings = [
        OrderTakeQueryContract::class => OrderTakeQuery::class,
        CustomerQueryContract::class  => CustomerQuery::class,
    ];
}
