<?php

declare(strict_types=1);

namespace App\Module\Car\Listeners\Integration;

use App\Module\Car\Commands\CreateCarOccupancyCommand;
use App\Module\Car\DTO\CarOccupancyDTO;

final class IntegrationCarOccupancyCreatedListener
{
    public function handle($event): void
    {
        dispatch(new CreateCarOccupancyCommand(CarOccupancyDTO::fromEvent($event->DTO)));
    }
}
