<?php

declare(strict_types=1);

namespace App\Module\City\Listeners\Integration;

use App\Module\City\Commands\CreateRegionCommand;
use App\Module\City\DTO\RegionDTO;

final class IntegrationRegionCreatedListener
{
    public function handle($event): void
    {
        dispatch(new CreateRegionCommand(RegionDTO::fromEvent($event)));
    }
}
