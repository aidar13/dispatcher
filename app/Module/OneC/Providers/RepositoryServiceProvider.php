<?php

declare(strict_types=1);

namespace App\Module\OneC\Providers;

use App\Module\OneC\Contracts\Integration\HttpClientOneC;
use App\Module\OneC\Http\Request;
use Illuminate\Support\ServiceProvider;

final class RepositoryServiceProvider extends ServiceProvider
{
    public array $bindings = [
        HttpClientOneC::class => Request::class
    ];
}
