<?php

declare(strict_types=1);

namespace App\Module\Order\Commands;

use App\Module\Order\DTO\CreateSlaDTO;

final class CreateSlaCommand
{
    public function __construct(public CreateSlaDTO $DTO)
    {
    }
}
