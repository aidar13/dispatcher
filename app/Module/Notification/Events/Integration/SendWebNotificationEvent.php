<?php

declare(strict_types=1);

namespace App\Module\Notification\Events\Integration;

use App\Module\Notification\DTO\WebNotificationDTO;
use Ludovicose\TransactionOutbox\Contracts\ShouldBePublish;

final class SendWebNotificationEvent implements ShouldBePublish
{
    public function __construct(
        public readonly WebNotificationDTO $DTO
    ) {
    }

    public function getChannel(): string
    {
        return 'web-notification.send';
    }
}
