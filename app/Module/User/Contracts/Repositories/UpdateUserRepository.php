<?php

declare(strict_types=1);

namespace App\Module\User\Contracts\Repositories;

use App\Models\User;

interface UpdateUserRepository
{
    public function update(User $user): void;
}
