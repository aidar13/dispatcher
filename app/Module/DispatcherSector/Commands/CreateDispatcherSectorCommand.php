<?php

declare(strict_types=1);

namespace App\Module\DispatcherSector\Commands;

use App\Module\DispatcherSector\DTO\CreateDispatcherSectorDTO;

final class CreateDispatcherSectorCommand
{
    public function __construct(public readonly CreateDispatcherSectorDTO $DTO)
    {
    }
}
