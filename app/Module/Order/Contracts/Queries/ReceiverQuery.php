<?php

declare(strict_types=1);

namespace App\Module\Order\Contracts\Queries;

use App\Module\Order\Models\Receiver;

interface ReceiverQuery
{
    public function getById(int $id): ?Receiver;
}
