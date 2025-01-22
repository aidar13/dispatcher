<?php

declare(strict_types=1);

namespace App\Module\File\Commands;

use Illuminate\Http\UploadedFile;

final class UploadFileToAwsCommand
{
    public function __construct(
        public readonly string $bucketName,
        public readonly UploadedFile $file,
    ) {
    }
}
