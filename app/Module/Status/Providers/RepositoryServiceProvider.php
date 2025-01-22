<?php

declare(strict_types=1);

namespace App\Module\Status\Providers;

use App\Module\Status\Contracts\Repositories\CreateOrderStatusRepository;
use App\Module\Status\Contracts\Repositories\CreateWaitListStatusRepository;
use App\Module\Status\Repositories\Eloquent\OrderStatusRepository;
use App\Module\Status\Repositories\Eloquent\WaitListStatusRepository;
use Illuminate\Support\ServiceProvider;

final class RepositoryServiceProvider extends ServiceProvider
{
    public array $bindings = [
        CreateOrderStatusRepository::class    => OrderStatusRepository::class,
        CreateWaitListStatusRepository::class => WaitListStatusRepository::class,
    ];
}
