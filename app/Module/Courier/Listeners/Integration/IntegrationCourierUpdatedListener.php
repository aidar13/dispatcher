<?php

declare(strict_types=1);

namespace App\Module\Courier\Listeners\Integration;

use App\Module\Courier\Commands\Integration\UpdateCourierCommand;
use App\Module\Courier\DTO\Integration\CourierDTO;

final class IntegrationCourierUpdatedListener
{
    public function handle($event): void
    {
        dispatch(new UpdateCourierCommand(CourierDTO::fromEvent($event->DTO)));
    }
}
