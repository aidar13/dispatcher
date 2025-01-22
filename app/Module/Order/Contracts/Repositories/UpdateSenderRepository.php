<?php

declare(strict_types=1);

namespace App\Module\Order\Contracts\Repositories;

use App\Module\Order\Models\Sender;

interface UpdateSenderRepository
{
    public function update(Sender $sender): void;
}
