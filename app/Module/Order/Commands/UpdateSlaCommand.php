<?php

declare(strict_types=1);

namespace App\Module\Order\Commands;

use App\Module\Order\DTO\UpdateSlaDTO;

final class UpdateSlaCommand
{
    public function __construct(public UpdateSlaDTO $DTO)
    {
    }
}
