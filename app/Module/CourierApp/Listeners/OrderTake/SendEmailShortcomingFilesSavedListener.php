<?php

declare(strict_types=1);

namespace App\Module\CourierApp\Listeners\OrderTake;

use App\Module\CourierApp\Commands\OrderTake\SendEmailShortcomingFilesSavedCommand;
use App\Module\CourierApp\Events\OrderTake\CourierShortcomingFilesSavedEvent;

final class SendEmailShortcomingFilesSavedListener
{
    public function handle(CourierShortcomingFilesSavedEvent $event): void
    {
        dispatch(new SendEmailShortcomingFilesSavedCommand($event->orderId));
    }
}
