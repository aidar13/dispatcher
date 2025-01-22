<?php

declare(strict_types=1);

namespace App\Module\Order\Contracts\Queries;

use App\Module\Order\Models\Sender;

interface SenderQuery
{
    public function getById(int $id): ?Sender;
}
