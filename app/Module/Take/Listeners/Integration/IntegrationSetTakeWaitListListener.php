<?php

declare(strict_types=1);

namespace App\Module\Take\Listeners\Integration;

use App\Module\Delivery\DTO\SetWaitListStatusDTO;
use App\Module\Take\Commands\SetTakeWaitListCommand;

final class IntegrationSetTakeWaitListListener
{
    public function handle($event): void
    {
        dispatch(new SetTakeWaitListCommand(SetWaitListStatusDTO::fromEvent($event->DTO)));
    }
}
