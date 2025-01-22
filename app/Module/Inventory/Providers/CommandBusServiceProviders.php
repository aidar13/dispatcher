<?php

declare(strict_types=1);

namespace App\Module\Inventory\Providers;

use App\Module\Inventory\Commands\Integration\CreateWriteOffCommand;
use App\Module\Inventory\Handlers\Integration\CreateWriteOffHandler;
use Illuminate\Support\Facades\Bus;
use Illuminate\Support\ServiceProvider;

class CommandBusServiceProviders extends ServiceProvider
{
    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot(): void
    {
        $this->registerCommandHandlers();
    }

    /**
     * @return void
     */
    private function registerCommandHandlers(): void
    {
        Bus::map([
            CreateWriteOffCommand::class => CreateWriteOffHandler::class
        ]);
    }
}
