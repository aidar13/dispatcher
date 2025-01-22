<?php

declare(strict_types=1);

namespace App\Module\Courier\Commands\Integration;

use App\Module\Courier\DTO\Integration\CourierDTO;

final class CreateCourierCommand
{
    public function __construct(public readonly CourierDTO $DTO)
    {
    }
}
