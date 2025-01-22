<?php

declare(strict_types=1);

namespace App\Module\Status\Handlers\Integration;

use App\Module\Status\Commands\Integration\CreateWaitListStatusCommand;
use App\Module\Status\Contracts\Queries\WaitListStatusQuery;
use App\Module\Status\Contracts\Repositories\CreateWaitListStatusRepository;
use App\Module\Status\Events\WaitListStatusCreatedEvent;
use App\Module\Status\Models\WaitListStatus;
use Illuminate\Database\Eloquent\ModelNotFoundException;

final readonly class CreateWaitListStatusHandler
{
    public function __construct(
        private WaitListStatusQuery $query,
        private CreateWaitListStatusRepository $repository,
    ) {
    }

    public function handle(CreateWaitListStatusCommand $command): void
    {
        if ($this->statusExists($command->DTO->id)) {
            return;
        }

        $model              = new WaitListStatus();
        $model->id          = $command->DTO->id;
        $model->client_id   = $command->DTO->clientId;
        $model->client_type = $command->DTO->clientType;
        $model->state_id    = $command->DTO->stateId;
        $model->value       = $command->DTO->value;
        $model->comment     = $command->DTO->comment;
        $model->code        = $command->DTO->code;
        $model->parent_id   = $command->DTO->parentId;
        $model->user_id     = $command->DTO->userId;
        $model->source      = $command->DTO->source;
        $model->created_at  = $command->DTO->createdAt;

        $this->repository->save($model);

        event(new WaitListStatusCreatedEvent($model->id));
    }

    private function statusExists(int $id): bool
    {
        try {
            return (bool)$this->query->getById($id);
        } catch (ModelNotFoundException) {
            return false;
        }
    }
}
