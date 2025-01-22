<?php

declare(strict_types=1);

namespace App\Module\Take\Commands;

use App\Module\Delivery\DTO\SetWaitListStatusDTO;

final class SetTakeWaitListCommand
{
    public function __construct(public readonly SetWaitListStatusDTO $DTO)
    {
    }
}
