<?php

declare(strict_types=1);

namespace App\Module\File\Handlers;

use App\Module\File\Commands\UploadFileToAwsCommand;
use App\Module\File\Exceptions\CannotUploadDocumentToS3Exception;
use Illuminate\Support\Facades\Storage;

final class UploadFileToAwsHandler
{
    /** @psalm-suppress ImplicitToStringCast  */
    public function handle(UploadFileToAwsCommand $command): string
    {
        $path = $command->file->store($command->bucketName, 's3');
        Storage::disk('s3')->setVisibility($path, 'public');

        if (!$path) {
            throw new CannotUploadDocumentToS3Exception(sprintf(
                "Не удалось загрузить документ bucketName = %s OriginalName = %s",
                $command->bucketName,
                $command->file->getClientOriginalName()
            ));
        }
        return $path;
    }
}
