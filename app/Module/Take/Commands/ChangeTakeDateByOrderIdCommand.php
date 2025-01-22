<?php

declare(strict_types=1);

namespace App\Module\Take\Commands;

use App\Module\Take\DTO\ChangeTakeDateDTO;

final class ChangeTakeDateByOrderIdCommand
{
    public function __construct(
        public ChangeTakeDateDTO $DTO
    ) {
    }
}
