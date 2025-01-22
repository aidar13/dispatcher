<?php

declare(strict_types=1);

namespace App\Module\User\Queries;

use App\Models\User;
use App\Module\User\Contracts\Queries\UserQuery as UserQueryContract;

final class UserQuery implements UserQueryContract
{
    public function getById(int $id): User
    {
        /** @var User */
        return User::query()
            ->findOrFail($id);
    }
}
