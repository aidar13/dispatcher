<?php

declare(strict_types=1);

namespace App\Module\Company\Providers;

use App\Module\Company\Contracts\Queries\CompanyQuery as CompanyQueryContract;
use App\Module\Company\Queries\Eloquent\CompanyQuery;
use Illuminate\Support\ServiceProvider;

final class QueryServiceProvider extends ServiceProvider
{
    public array $bindings = [
        CompanyQueryContract::class  => CompanyQuery::class,
    ];
}
