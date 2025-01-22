<?php

declare(strict_types=1);

namespace App\Module\Gateway\Providers;

use App\Module\Gateway\Commands\CreateTokenCommand;
use App\Module\Gateway\Commands\UpdateTokenCommand;
use App\Module\Gateway\Handlers\CreateTokenHandler;
use App\Module\Gateway\Handlers\UpdateTokenHandler;
use Illuminate\Support\Facades\Bus;
use Illuminate\Support\ServiceProvider;

class CommandBusServiceProvider extends ServiceProvider
{
    private array $maps = [
        CreateTokenCommand::class => CreateTokenHandler::class,
        UpdateTokenCommand::class => UpdateTokenHandler::class,
    ];

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerCommandHandlers();
    }

    private function registerCommandHandlers()
    {
        Bus::map($this->maps);
    }
}
