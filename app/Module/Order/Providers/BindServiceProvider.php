<?php

declare(strict_types=1);

namespace App\Module\Order\Providers;

use App\Module\Order\Services\InvoiceService;
use App\Module\Order\Contracts\Services\InvoiceService as InvoiceServiceContract;
use App\Module\Order\Contracts\Services\OrderService as OrderServiceContract;
use App\Module\Order\Services\OrderService;
use Illuminate\Support\ServiceProvider;

class BindServiceProvider extends ServiceProvider
{
    public array $bindings = [
        InvoiceServiceContract::class => InvoiceService::class,
        OrderServiceContract::class   => OrderService::class,
    ];
}
