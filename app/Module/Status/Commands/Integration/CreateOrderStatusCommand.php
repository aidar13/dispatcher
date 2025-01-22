<?php

declare(strict_types=1);

namespace App\Module\Status\Commands\Integration;

use App\Module\Status\DTO\Integration\CreateOrderStatusDTO;

final class CreateOrderStatusCommand
{
    public function __construct(public CreateOrderStatusDTO $DTO)
    {
    }
}
