<?php

declare(strict_types=1);

namespace App\Module\Car\Listeners\Integration;

use App\Module\Car\Commands\UpdateCarCommand;
use App\Module\Car\DTO\CarDTO;

final class IntegrationCarUpdatedListener
{
    public function handle($event): void
    {
        dispatch(new UpdateCarCommand(CarDTO::fromEvent($event->DTO)));
    }
}
