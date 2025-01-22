<?php

declare(strict_types=1);

namespace App\Module\Planning\Commands;

use App\Module\Planning\DTO\AssignCourierToContainerDTO;

final class AssignCourierToContainersCommand
{
    public function __construct(public readonly AssignCourierToContainerDTO $DTO)
    {
    }
}
