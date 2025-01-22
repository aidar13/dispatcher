<?php

declare(strict_types=1);

namespace App\Module\File\Providers;

use App\Module\File\Contracts\Queries\FileQuery as FileQueryContract;
use App\Module\File\Queries\Eloquent\FileQuery;
use Illuminate\Support\ServiceProvider;

final class QueryServiceProvider extends ServiceProvider
{
    public array $bindings = [
        FileQueryContract::class => FileQuery::class
    ];
}
