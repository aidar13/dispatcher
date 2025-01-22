<?php

declare(strict_types=1);

namespace App\Module\Notification\Handlers;

use App\Module\Notification\Commands\SendTelegramMessageCommand;
use App\Module\Notification\Contracts\Repositories\SendTelegramMessageRepository;
use App\Module\Settings\Contracts\Services\SettingsService;
use App\Module\Settings\Models\Settings;

class SendTelegramMessageHandler
{
    public function __construct(
        private readonly SettingsService $settingsService,
        private readonly SendTelegramMessageRepository $repository
    ) {
    }

    public function handle(SendTelegramMessageCommand $command): void
    {
        if (!$this->settingsService->isEnabled(Settings::TELEGRAM_MESSAGE)) {
            return;
        }

        $this->repository->send(
            $command->chatId,
            $command->message
        );
    }
}
