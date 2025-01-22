<?php

declare(strict_types=1);

namespace App\Module\Order\Listeners;

use App\Module\Order\Commands\CreateSlaCommand;
use App\Module\Order\DTO\CreateSlaDTO;

final class IntegrationSlaCreatedListener
{
    public function handle($event): void
    {
        dispatch(new CreateSlaCommand(CreateSlaDTO::fromEvent($event)));
    }
}
