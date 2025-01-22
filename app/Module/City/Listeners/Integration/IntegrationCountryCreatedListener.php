<?php

declare(strict_types=1);

namespace App\Module\City\Listeners\Integration;

use App\Module\City\Commands\CreateCountryCommand;
use App\Module\City\DTO\CountryDTO;

final class IntegrationCountryCreatedListener
{
    public function handle($event): void
    {
        dispatch(new CreateCountryCommand(CountryDTO::fromEvent($event)));
    }
}
