<?php

declare(strict_types=1);

namespace App\Module\Planning\Events;

use App\Module\Planning\DTO\ChangeContainerStatusDTO;

final class ContainerStatusUpdatedEvent
{
    public function __construct(
        public readonly ChangeContainerStatusDTO $DTO
    ) {
    }
}
