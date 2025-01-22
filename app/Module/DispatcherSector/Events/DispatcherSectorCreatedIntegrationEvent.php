<?php

declare(strict_types=1);

namespace App\Module\DispatcherSector\Events;

use App\Module\DispatcherSector\DTO\IntegrationDispatcherSectorDTO;
use Ludovicose\TransactionOutbox\Contracts\ShouldBePublish;

final class DispatcherSectorCreatedIntegrationEvent implements ShouldBePublish
{
    public function __construct(
        public IntegrationDispatcherSectorDTO $DTO
    ) {
    }

    public function getChannel(): string
    {
        return 'dispatcher-sector.created';
    }
}
