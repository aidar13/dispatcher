<?php

declare(strict_types=1);

namespace App\Module\Notification\Providers;

use App\Module\Notification\Commands\SendTelegramMessageCommand;
use App\Module\Notification\Handlers\SendTelegramMessageHandler;
use Illuminate\Support\Facades\Bus;
use Illuminate\Support\ServiceProvider;

final class CommandBusServiceProviders extends ServiceProvider
{
    public function boot(): void
    {
        Bus::map(array(
            SendTelegramMessageCommand::class => SendTelegramMessageHandler::class,
        ));
    }
}
