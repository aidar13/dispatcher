<?php

declare(strict_types=1);

namespace App\Module\User\Providers;

use App\Module\User\Commands\UpdateUserCommand;
use App\Module\User\Handlers\UpdateUserHandler;
use Illuminate\Support\Facades\Bus;
use Illuminate\Support\ServiceProvider;

final class CommandBusServiceProviders extends ServiceProvider
{
    private array $maps = [
        UpdateUserCommand::class => UpdateUserHandler::class,
    ];

    public function boot(): void
    {
        $this->registerCommandHandlers();
    }

    private function registerCommandHandlers(): void
    {
        Bus::map($this->maps);
    }
}
