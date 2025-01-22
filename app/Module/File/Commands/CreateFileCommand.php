<?php

declare(strict_types=1);

namespace App\Module\File\Commands;

use Illuminate\Http\UploadedFile;

final readonly class CreateFileCommand
{
    public function __construct(
        public UploadedFile $file,
        public int $type,
        public string $bucketName,
        public string $originalName,
        public int $clientId,
        public string $clientType,
        public int $userId,
    ) {
    }
}
