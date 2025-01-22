<?php

declare(strict_types=1);

namespace App\Module\Courier\Commands;

use App\Module\File\DTO\UploadFilesDTO;

final class UpLoadCourierFilesCommand
{
    public function __construct(
        public readonly int $userId,
        public readonly int $courierId,
        public readonly UploadFilesDTO $uploadDocumentDTO
    ) {
    }
}
