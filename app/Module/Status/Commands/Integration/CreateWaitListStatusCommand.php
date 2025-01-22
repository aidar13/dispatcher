<?php

declare(strict_types=1);

namespace App\Module\Status\Commands\Integration;

use App\Module\Status\DTO\Integration\StoreWaitListStatusDTO;

final class CreateWaitListStatusCommand
{
    public function __construct(public StoreWaitListStatusDTO $DTO)
    {
    }
}
