<?php

declare(strict_types=1);

namespace App\Module\Take\Listeners\Integration;

use App\Module\Status\Models\RefStatus;
use App\Module\Take\Commands\SetWaitListStatusCommand;
use App\Module\Take\Events\ChangedTakeDateByOrderEvent;

final class SetChangedTakeDateWaitListStatusRepositoryListener
{
    public function handle(ChangedTakeDateByOrderEvent $event): void
    {
//        dispatch(new SetWaitListStatusCommand(
//            $event->DTO->orderId,
//            RefStatus::CODE_CHANGED_TAKE_DATE,
//            $event->DTO->userId
//        ));
    }
}
