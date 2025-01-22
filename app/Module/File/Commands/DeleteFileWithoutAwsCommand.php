<?php

declare(strict_types=1);

namespace App\Module\File\Commands;

final class DeleteFileWithoutAwsCommand
{
    public function __construct(
        public readonly string $uuidHash,
    ) {
    }
}
