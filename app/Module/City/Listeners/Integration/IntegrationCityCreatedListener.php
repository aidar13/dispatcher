<?php

declare(strict_types=1);

namespace App\Module\City\Listeners\Integration;

use App\Module\City\Commands\CreateCityCommand;
use App\Module\City\DTO\CityDTO;

final class IntegrationCityCreatedListener
{
    public function handle($event): void
    {
        dispatch(new CreateCityCommand(CityDTO::fromEvent($event)));
    }
}
