<?php

declare(strict_types=1);

namespace App\Module\CourierApp\Commands\OrderTake;

use App\Module\CourierApp\DTO\OrderTake\SaveShortcomingFilesDTO;

final readonly class SaveCourierShortcomingFilesCommand
{
    public function __construct(
        public int $userId,
        public SaveShortcomingFilesDTO $DTO,
    ) {
    }
}
