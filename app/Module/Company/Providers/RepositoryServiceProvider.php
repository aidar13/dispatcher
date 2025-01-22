<?php

declare(strict_types=1);

namespace App\Module\Company\Providers;

use App\Module\Company\Contracts\Repositories\CreateCompanyRepository;
use App\Module\Company\Contracts\Repositories\UpdateCompanyRepository;
use App\Module\Company\Repositories\CompanyRepository;
use App\Module\Company\Repositories\Decorators\CreateCompanyLogRepository;
use App\Module\Company\Repositories\Decorators\UpdateCompanyLogRepository;
use Illuminate\Support\ServiceProvider;

final class RepositoryServiceProvider extends ServiceProvider
{
    public array $bindings = [
        CreateCompanyRepository::class => CreateCompanyLogRepository::class,
        UpdateCompanyRepository::class => UpdateCompanyLogRepository::class,
    ];

    public function register(): void
    {
        $this->app->when(CreateCompanyLogRepository::class)
            ->needs(CreateCompanyRepository::class)
            ->give(CompanyRepository::class);

        $this->app->when(UpdateCompanyLogRepository::class)
            ->needs(UpdateCompanyRepository::class)
            ->give(CompanyRepository::class);
    }
}
