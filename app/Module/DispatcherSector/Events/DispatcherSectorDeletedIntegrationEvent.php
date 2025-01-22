<?php

declare(strict_types=1);

namespace App\Module\DispatcherSector\Events;

use Ludovicose\TransactionOutbox\Contracts\ShouldBePublish;

final class DispatcherSectorDeletedIntegrationEvent implements ShouldBePublish
{
    public function __construct(
        public int $id
    ) {
    }

    public function getChannel(): string
    {
        return 'dispatcher-sector.deleted';
    }
}
