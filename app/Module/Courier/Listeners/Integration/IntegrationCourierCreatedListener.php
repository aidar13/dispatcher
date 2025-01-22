<?php

declare(strict_types=1);

namespace App\Module\Courier\Listeners\Integration;

use App\Module\Courier\Commands\Integration\CreateCourierCommand;
use App\Module\Courier\DTO\Integration\CourierDTO;

final class IntegrationCourierCreatedListener
{
    public function handle($event): void
    {
        dispatch(new CreateCourierCommand(CourierDTO::fromEvent($event->DTO)));
    }
}
