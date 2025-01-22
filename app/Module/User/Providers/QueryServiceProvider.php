<?php

declare(strict_types=1);

namespace App\Module\User\Providers;

use App\Module\User\Contracts\Queries\UserQuery as UserQueryContract;
use App\Module\User\Queries\UserQuery;
use Illuminate\Support\ServiceProvider;

final class QueryServiceProvider extends ServiceProvider
{
    public array $bindings = [
        UserQueryContract::class => UserQuery::class,
    ];
}
