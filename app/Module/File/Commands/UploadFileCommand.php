<?php

declare(strict_types=1);

namespace App\Module\File\Commands;

use App\Module\File\DTO\UploadFileDTO;

final class UploadFileCommand
{
    public function __construct(
        public readonly int $userId,
        public readonly UploadFileDTO $DTO,
    ) {
    }
}
