<?php

declare(strict_types=1);

namespace App\Module\CourierApp\Commands\OrderTake;

use App\Module\CourierApp\DTO\WaitListStatus\SetWaitListStatusDTO;

final class SetTakeInfoWaitListStatusCommand
{
    public function __construct(
        public readonly int $takeId,
        public readonly SetWaitListStatusDTO $DTO
    ) {
    }
}
