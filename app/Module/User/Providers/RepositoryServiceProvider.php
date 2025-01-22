<?php

declare(strict_types=1);

namespace App\Module\User\Providers;

use App\Module\User\Contracts\Repositories\UpdateUserRepository;
use App\Module\User\Repositories\UserRepository;
use Illuminate\Support\ServiceProvider;

final class RepositoryServiceProvider extends ServiceProvider
{
    public array $bindings = [
        UpdateUserRepository::class => UserRepository::class,
    ];
}
