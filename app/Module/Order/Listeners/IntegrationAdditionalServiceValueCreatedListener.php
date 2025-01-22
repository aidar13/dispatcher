<?php

declare(strict_types=1);

namespace App\Module\Order\Listeners;

use App\Module\Order\Commands\CreateAdditionalServiceValueCommand;
use App\Module\Order\DTO\AdditionalServiceValueDTO;

final class IntegrationAdditionalServiceValueCreatedListener
{
    public function handle($event): void
    {
        dispatch(new CreateAdditionalServiceValueCommand(AdditionalServiceValueDTO::fromEvent($event->DTO)));
    }
}
