<?php

declare(strict_types=1);

namespace App\Module\Order\Listeners;

use App\Module\Order\Commands\UpdateSlaCommand;
use App\Module\Order\DTO\UpdateSlaDTO;

final class IntegrationSlaUpdatedListener
{
    public function handle($event): void
    {
        dispatch(new UpdateSlaCommand(UpdateSlaDTO::fromEvent($event)));
    }
}
