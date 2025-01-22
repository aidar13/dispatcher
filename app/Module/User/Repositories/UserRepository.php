<?php

declare(strict_types=1);

namespace App\Module\User\Repositories;

use App\Models\User;
use App\Module\User\Contracts\Repositories\UpdateUserRepository;

final class UserRepository implements UpdateUserRepository
{
    /**
     * @throws \Throwable
     */
    public function update(User $user): void
    {
        $user->updateOrFail();
    }
}
