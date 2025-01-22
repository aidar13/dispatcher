<?php

declare(strict_types=1);

namespace App\Module\DispatcherSector\Commands\Integration;

use App\Module\DispatcherSector\DTO\Integration\DispatcherSectorDTO;

final class CreateDispatcherSectorCommand
{
    public function __construct(public readonly DispatcherSectorDTO $DTO)
    {
    }
}
