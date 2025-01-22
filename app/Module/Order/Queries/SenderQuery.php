<?php

declare(strict_types=1);

namespace App\Module\Order\Queries;

use App\Module\Order\Contracts\Queries\SenderQuery as SenderQueryContract;
use App\Module\Order\Models\Sender;

final class SenderQuery implements SenderQueryContract
{
    public function getById(int $id): ?Sender
    {
        return Sender::find($id);
    }
}
