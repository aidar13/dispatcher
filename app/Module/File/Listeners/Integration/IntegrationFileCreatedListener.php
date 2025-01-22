<?php

declare(strict_types=1);

namespace App\Module\File\Listeners\Integration;

use App\Module\File\Commands\Integration\IntegrationCreateFileCommand;
use App\Module\File\DTO\Integration\IntegrationFileDTO;
use App\Module\File\Models\File;

final class IntegrationFileCreatedListener
{
    public function handle($event): void
    {
        $dto             = IntegrationFileDTO::fromEvent($event->DTO);
        $dto->clientType = File::getClientTypeByClientType($event->DTO->clientType);

        dispatch(new IntegrationCreateFileCommand($dto));
    }
}
