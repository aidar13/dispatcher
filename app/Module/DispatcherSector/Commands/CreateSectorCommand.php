<?php

declare(strict_types=1);

namespace App\Module\DispatcherSector\Commands;

use App\Module\DispatcherSector\DTO\CreateSectorDTO;

final class CreateSectorCommand
{
    public function __construct(public readonly CreateSectorDTO $DTO)
    {
    }
}
