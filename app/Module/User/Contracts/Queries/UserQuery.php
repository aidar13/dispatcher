<?php

declare(strict_types=1);

namespace App\Module\User\Contracts\Queries;

use App\Models\User;

interface UserQuery
{
    public function getById(int $id): User;
}
