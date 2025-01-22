<?php

namespace App\Module\Notification\Providers;

use App\Module\Notification\Contracts\Repositories\SendEmailNotificationRepository;
use App\Module\Notification\Contracts\Repositories\SendPushNotificationRepository;
use App\Module\Notification\Contracts\Repositories\SendTelegramMessageRepository;
use App\Module\Notification\Repositories\EmailNotificationRepository;
use App\Module\Notification\Repositories\PushNotificationRepository;
use App\Module\Notification\Repositories\TelegramNotificationRepository;
use Illuminate\Support\ServiceProvider;

final class RepositoryServiceProvider extends ServiceProvider
{
    public array $bindings = [
        // Repository
        SendEmailNotificationRepository::class => EmailNotificationRepository::class,
        SendTelegramMessageRepository::class   => TelegramNotificationRepository::class,
        SendPushNotificationRepository::class  => PushNotificationRepository::class,
    ];
}
