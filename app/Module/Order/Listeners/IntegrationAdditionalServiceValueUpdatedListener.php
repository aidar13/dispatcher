<?php

declare(strict_types=1);

namespace App\Module\Order\Listeners;

use App\Module\Order\Commands\UpdateAdditionalServiceValueCommand;
use App\Module\Order\DTO\AdditionalServiceValueDTO;

final class IntegrationAdditionalServiceValueUpdatedListener
{
    public function handle($event): void
    {
        dispatch(new UpdateAdditionalServiceValueCommand(AdditionalServiceValueDTO::fromEvent($event->DTO)));
    }
}
