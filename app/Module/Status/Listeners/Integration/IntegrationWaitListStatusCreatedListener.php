<?php

declare(strict_types=1);

namespace App\Module\Status\Listeners\Integration;

use App\Module\Status\Commands\Integration\CreateWaitListStatusCommand;
use App\Module\Status\DTO\Integration\StoreWaitListStatusDTO;

final class IntegrationWaitListStatusCreatedListener
{
    public function handle($event): void
    {
        dispatch(new CreateWaitListStatusCommand(StoreWaitListStatusDTO::fromEvent($event)));
    }
}
