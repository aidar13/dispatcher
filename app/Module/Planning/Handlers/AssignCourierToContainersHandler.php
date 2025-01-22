<?php

declare(strict_types=1);

namespace App\Module\Planning\Handlers;

use App\Module\Planning\Commands\AssignCourierToContainerCommand;
use App\Module\Planning\Commands\AssignCourierToContainersCommand;

final class AssignCourierToContainersHandler
{
    public function handle(AssignCourierToContainersCommand $command): void
    {
        foreach ($command->DTO->containerIds as $containerId) {
            dispatch(new AssignCourierToContainerCommand(
                $containerId,
                $command->DTO->courierId,
                $command->DTO->providerId,
            ));
        }
    }
}
