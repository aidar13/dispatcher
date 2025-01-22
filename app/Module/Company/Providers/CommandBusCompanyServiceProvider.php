<?php

declare(strict_types=1);

namespace App\Module\Company\Providers;

use App\Module\Company\Commands\CreateCompanyCommand;
use App\Module\Company\Commands\UpdateCompanyCommand;
use App\Module\Company\Handlers\CreateCompanyHandler;
use App\Module\Company\Handlers\UpdateCompanyHandler;
use Illuminate\Support\Facades\Bus;
use Illuminate\Support\ServiceProvider;

final class CommandBusCompanyServiceProvider extends ServiceProvider
{
    private array $maps = [
        CreateCompanyCommand::class => CreateCompanyHandler::class,
        UpdateCompanyCommand::class => UpdateCompanyHandler::class,
    ];

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot(): void
    {
        $this->registerCommandHandlers();
    }

    private function registerCommandHandlers(): void
    {
        Bus::map($this->maps);
    }
}
