<?php

declare(strict_types=1);

namespace App\Module\Planning\Providers;

use App\Module\Planning\Contracts\Repositories\CreateContainerRepository;
use App\Module\Planning\Contracts\Repositories\CreateContainerInvoiceRepository;
use App\Module\Planning\Contracts\Repositories\DeleteContainerRepository;
use App\Module\Planning\Contracts\Repositories\DeleteContainerInvoiceRepository;
use App\Module\Planning\Contracts\Repositories\Integration\SendToAssemblyRepository;
use App\Module\Planning\Contracts\Repositories\UpdateContainerInvoiceRepository;
use App\Module\Planning\Contracts\Repositories\UpdateContainerRepository;
use App\Module\Planning\Repositories\Eloquent\ContainerInvoiceRepository;
use App\Module\Planning\Repositories\Eloquent\ContainerRepository;
use App\Module\Planning\Repositories\Http\ContainerRepository as HttpContainerRepository;
use Illuminate\Support\ServiceProvider;

final class RepositoryServiceProvider extends ServiceProvider
{
    public array $bindings = [
        CreateContainerRepository::class        => ContainerRepository::class,
        UpdateContainerRepository::class        => ContainerRepository::class,
        CreateContainerInvoiceRepository::class => ContainerInvoiceRepository::class,
        DeleteContainerInvoiceRepository::class => ContainerInvoiceRepository::class,
        UpdateContainerInvoiceRepository::class => ContainerInvoiceRepository::class,
        SendToAssemblyRepository::class         => HttpContainerRepository::class,
        DeleteContainerRepository::class        => ContainerRepository::class
    ];
}
