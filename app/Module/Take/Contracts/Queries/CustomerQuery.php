<?php

declare(strict_types=1);

namespace App\Module\Take\Contracts\Queries;

use App\Module\Take\Models\Customer;
use Illuminate\Database\Eloquent\Collection;

interface CustomerQuery
{
    public function getById(int $id): ?Customer;

    public function getAllBySenderId(int $senderId): Collection;

    public function getAllByReceiverId(int $receiverId): Collection;
}
