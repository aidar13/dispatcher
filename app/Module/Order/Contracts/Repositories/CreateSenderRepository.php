<?php

declare(strict_types=1);

namespace App\Module\Order\Contracts\Repositories;

use App\Module\Order\Models\Sender;

interface CreateSenderRepository
{
    public function create(Sender $sender): void;
}
