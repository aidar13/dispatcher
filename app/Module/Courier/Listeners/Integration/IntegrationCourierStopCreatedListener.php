<?php

declare(strict_types=1);

namespace App\Module\Courier\Listeners\Integration;

use App\Module\Courier\Commands\Integration\CreateCourierStopCommand;
use App\Module\Courier\DTO\Integration\CourierStopDTO;

final class IntegrationCourierStopCreatedListener
{
    public function handle($event): void
    {
        dispatch(new CreateCourierStopCommand(CourierStopDTO::fromEvent($event->DTO)));
    }
}
