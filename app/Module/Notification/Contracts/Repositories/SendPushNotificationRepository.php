<?php

declare(strict_types=1);

namespace App\Module\Notification\Contracts\Repositories;

use App\Module\Notification\DTO\PushNotificationDTO;

interface SendPushNotificationRepository
{
    public function send(PushNotificationDTO $DTO);
}
