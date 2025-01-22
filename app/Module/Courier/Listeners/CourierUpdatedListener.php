<?php

declare(strict_types=1);

namespace App\Module\Courier\Listeners;

use App\Module\Courier\Commands\UpdateCourierPhoneNumberInGatewayCommand;
use App\Module\Courier\Events\CourierUpdatedEvent;

final class CourierUpdatedListener
{
    public function handle(CourierUpdatedEvent $event): void
    {
        dispatch(new UpdateCourierPhoneNumberInGatewayCommand($event->id));
    }
}
