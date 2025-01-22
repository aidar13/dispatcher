<?php

declare(strict_types=1);

namespace App\Module\Take\Events;

use App\Module\Take\DTO\ChangeTakeDateDTO;

final class ChangedTakeDateByOrderEvent
{
    public function __construct(
        public readonly ChangeTakeDateDTO $DTO
    ) {
    }
}
