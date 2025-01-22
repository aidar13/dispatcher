<?php

declare(strict_types=1);

namespace App\Module\CourierApp\Handlers\OrderTake;

use App\Module\Courier\Models\Courier;
use App\Module\CourierApp\Commands\OrderTake\SaveCourierShortcomingFilesCommand;
use App\Module\CourierApp\Events\OrderTake\CourierShortcomingFilesSavedEvent;
use App\Module\File\Commands\CreateFileCommand;
use App\Module\File\Models\File;
use App\Module\Order\Models\Order;

final class SaveCourierShortcomingFilesHandler
{
    public function handle(SaveCourierShortcomingFilesCommand $command): void
    {
        foreach ($command->DTO->productFiles as $file) {
            dispatch(new CreateFileCommand(
                $file,
                File::TYPE_COURIER_SHORTCOMING_PRODUCT,
                Courier::SHORTCOMING_BUCKET_NAME,
                $file->getClientOriginalName(),
                $command->DTO->orderId,
                Order::class,
                $command->userId,
            ));
        }

        foreach ($command->DTO->shortcomingFiles as $file) {
            dispatch(new CreateFileCommand(
                $file,
                File::TYPE_COURIER_SHORTCOMING_REPORT,
                Courier::SHORTCOMING_BUCKET_NAME,
                $file->getClientOriginalName(),
                $command->DTO->orderId,
                Order::class,
                $command->userId,
            ));
        }

        event(new CourierShortcomingFilesSavedEvent($command->DTO->orderId));
    }
}
