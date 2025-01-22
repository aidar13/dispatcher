<?php

declare(strict_types=1);

namespace App\Module\Courier\Handlers;

use App\Module\Courier\Commands\UpLoadCourierFilesCommand;
use App\Module\Courier\Contracts\Queries\CourierQuery;
use App\Module\Courier\Models\Courier;
use App\Module\File\Commands\CreateFileCommand;

final class UpLoadCourierFilesHandler
{
    public function __construct(
        private readonly CourierQuery $courierQuery
    ) {
    }

    public function handle(UpLoadCourierFilesCommand $command): void
    {
        $courier = $this->courierQuery->getById($command->courierId);

        foreach ($command->uploadDocumentDTO->files as $file) {
            dispatch(new CreateFileCommand(
                $file,
                $command->uploadDocumentDTO->type,
                Courier::DOCUMENT_PATH,
                $file->getClientOriginalName(),
                $courier->id,
                get_class($courier),
                $command->userId,
            ));
        }
    }
}
