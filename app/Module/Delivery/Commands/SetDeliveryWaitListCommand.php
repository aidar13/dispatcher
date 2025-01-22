<?php

declare(strict_types=1);

namespace App\Module\Delivery\Commands;

use App\Module\Delivery\DTO\SetWaitListStatusDTO;

final class SetDeliveryWaitListCommand
{
    public function __construct(public readonly SetWaitListStatusDTO $DTO)
    {
    }
}
