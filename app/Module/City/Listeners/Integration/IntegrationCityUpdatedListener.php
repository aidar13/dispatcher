<?php

declare(strict_types=1);

namespace App\Module\City\Listeners\Integration;

use App\Module\City\Commands\UpdateCityCommand;
use App\Module\City\DTO\CityDTO;

final class IntegrationCityUpdatedListener
{
    public function handle($event): void
    {
        dispatch(new UpdateCityCommand(CityDTO::fromEvent($event)));
    }
}
