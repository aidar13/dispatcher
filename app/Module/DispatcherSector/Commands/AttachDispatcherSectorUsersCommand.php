<?php

declare(strict_types=1);

namespace App\Module\DispatcherSector\Commands;

final class AttachDispatcherSectorUsersCommand
{
    public function __construct(
        public readonly int $id,
        public readonly array $dispatcherIds,
    ) {
    }
}
