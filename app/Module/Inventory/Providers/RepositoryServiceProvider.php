<?php

declare(strict_types=1);

namespace App\Module\Inventory\Providers;

use App\Module\Inventory\Contracts\Repositories\CreateWriteOffRepository;
use App\Module\Inventory\Repositories\WriteOffRepository;
use Illuminate\Support\ServiceProvider;

final class RepositoryServiceProvider extends ServiceProvider
{
    public array $bindings = [
        CreateWriteOffRepository::class => WriteOffRepository::class,
    ];
}
