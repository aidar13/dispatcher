<?php

declare(strict_types=1);

namespace App\Module\File\Commands;

final class DeleteFileCommand
{
    public function __construct(
        public readonly int $id,
    ) {
    }
}
