<?php

declare(strict_types=1);

namespace App\Module\Car\Commands;

use App\Module\Car\DTO\CarDTO;

final class CreateCarCommand
{
    public function __construct(public readonly CarDTO $DTO)
    {
    }
}
