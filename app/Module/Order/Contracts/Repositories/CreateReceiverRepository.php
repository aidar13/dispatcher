<?php

declare(strict_types=1);

namespace App\Module\Order\Contracts\Repositories;

use App\Module\Order\Models\Receiver;

interface CreateReceiverRepository
{
    public function create(Receiver $receiver): void;
}
