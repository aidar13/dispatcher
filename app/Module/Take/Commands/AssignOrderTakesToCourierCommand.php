<?php

declare(strict_types=1);

namespace App\Module\Take\Commands;

use App\Module\Take\DTO\AssignOrderTakeDTO;

final class AssignOrderTakesToCourierCommand
{
    public function __construct(
        public readonly AssignOrderTakeDTO $DTO,
        public readonly int $userId
    ) {
    }
}
