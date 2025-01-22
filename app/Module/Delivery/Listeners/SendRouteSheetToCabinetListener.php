<?php

declare(strict_types=1);

namespace App\Module\Delivery\Listeners;

use App\Module\Delivery\Commands\SendRouteSheetToCabinetCommand;
use App\Module\Delivery\Events\RouteSheetCreatedFromOneCEvent;

final class SendRouteSheetToCabinetListener
{
    public function handle(RouteSheetCreatedFromOneCEvent $event): void
    {
        dispatch(new SendRouteSheetToCabinetCommand($event->routeSheetId));
    }
}
