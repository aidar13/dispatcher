<?php

declare(strict_types=1);

namespace App\Module\Order\Listeners;

use App\Module\Order\Commands\DeleteAdditionalServiceValueCommand;

final readonly class IntegrationAdditionalServiceValueDeletedListener
{
    public function handle($event): void
    {
        dispatch(new DeleteAdditionalServiceValueCommand((int)$event->id));
    }
}
