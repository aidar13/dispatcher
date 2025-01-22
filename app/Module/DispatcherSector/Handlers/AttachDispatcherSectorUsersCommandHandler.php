<?php

declare(strict_types=1);

namespace App\Module\DispatcherSector\Handlers;

use App\Module\DispatcherSector\Commands\AttachDispatcherSectorUsersCommand;
use App\Module\DispatcherSector\Contracts\Queries\DispatcherSectorQuery;
use App\Module\DispatcherSector\Contracts\Repositories\AttachDispatcherSectorUsersRepository;
use App\Module\DispatcherSector\Models\DispatchersSectorUser;

final class AttachDispatcherSectorUsersCommandHandler
{
    public function __construct(
        private readonly DispatcherSectorQuery $query,
        private readonly AttachDispatcherSectorUsersRepository $attachDispatcherSectorUsersRepository
    ) {
    }

    public function handle(AttachDispatcherSectorUsersCommand $command): void
    {
        $dispatcherSector = $this->query->getById($command->id);

        $dispatcherSector->dispatcherSectorUsers()->delete();

        foreach ($command->dispatcherIds as $userId) {
            $model                       = new DispatchersSectorUser();
            $model->user_id              = $userId;
            $model->dispatcher_sector_id = $dispatcherSector->id;

            $this->attachDispatcherSectorUsersRepository->attachUsers($model);
        }
    }
}
