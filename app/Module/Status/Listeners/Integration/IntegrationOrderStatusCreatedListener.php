<?php

declare(strict_types=1);

namespace App\Module\Status\Listeners\Integration;

use App\Module\Notification\Commands\SendTelegramMessageCommand;
use App\Module\Notification\Models\TelegramChat;
use App\Module\Status\Commands\Integration\CreateOrderStatusCommand;
use App\Module\Status\DTO\Integration\CreateOrderStatusDTO;

final class IntegrationOrderStatusCreatedListener
{
    public function handle($event): void
    {
        $dto = CreateOrderStatusDTO::fromEvent($event);

        try {
            dispatch_sync(new CreateOrderStatusCommand($dto));
        } catch (\Exception $exception) {
            dispatch(new SendTelegramMessageCommand(
                TelegramChat::ALERT_CHAT_ID,
                $exception->getMessage()
            ));
        }
    }
}
