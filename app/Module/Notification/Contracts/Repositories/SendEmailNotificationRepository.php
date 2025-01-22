<?php

declare(strict_types=1);

namespace App\Module\Notification\Contracts\Repositories;

use App\Module\Notification\DTO\EmailNotificationDTO;

interface SendEmailNotificationRepository
{
    public function send(EmailNotificationDTO $DTO);
}
