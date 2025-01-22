<?php

declare(strict_types=1);

namespace App\Module\Order\Queries;

use App\Module\Order\Contracts\Queries\ReceiverQuery as ReceiverQueryContract;
use App\Module\Order\Models\Receiver;

final class ReceiverQuery implements ReceiverQueryContract
{
    public function getById(int $id): ?Receiver
    {
        return Receiver::find($id);
    }
}
