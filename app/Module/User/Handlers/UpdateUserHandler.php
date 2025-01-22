<?php

declare(strict_types=1);

namespace App\Module\User\Handlers;

use App\Module\Gateway\Contracts\GatewayUserQuery;
use App\Module\User\Commands\UpdateUserCommand;
use App\Module\User\Contracts\Queries\UserQuery;
use App\Module\User\Contracts\Repositories\UpdateUserRepository;

final class UpdateUserHandler
{
    public function __construct(
        private readonly UserQuery $query,
        private readonly GatewayUserQuery $gatewayUserQuery,
        private readonly UpdateUserRepository $repository,
    ) {
    }

    public function handle(UpdateUserCommand $command): void
    {
        $user = $this->query->getById($command->userId);
        $DTO  = $this->gatewayUserQuery->find($command->userId);

        $user->name  = $DTO->name;
        $user->email = $DTO->email;
        $user->phone = $DTO->phone;

        $this->repository->update($user);
    }
}
