<?php

declare(strict_types=1);

namespace App\Module\CRM\Providers;

use App\Module\CRM\Contracts\Repositories\CreateClientAndDealRepository as CreateClientAndDealRepositoryContract;
use App\Module\CRM\Repositories\Integration\CreateClientAndDealRepository;
use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    public array $bindings = [
        // Repository
        CreateClientAndDealRepositoryContract::class => CreateClientAndDealRepository::class
    ];
}
