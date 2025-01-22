<?php

namespace App\Module\Courier\Commands\Integration;

use App\Module\Courier\DTO\Integration\CourierStopDTO;

final class CreateCourierStopCommand
{
    public function __construct(
        public readonly CourierStopDTO $DTO,
    ) {
    }
}
