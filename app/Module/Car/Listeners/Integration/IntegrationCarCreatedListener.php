<?php

declare(strict_types=1);

namespace App\Module\Car\Listeners\Integration;

use App\Module\Car\Commands\CreateCarCommand;
use App\Module\Car\DTO\CarDTO;

final class IntegrationCarCreatedListener
{
    public function handle($event): void
    {
        dispatch(new CreateCarCommand(CarDTO::fromEvent($event->DTO)));
    }
}
