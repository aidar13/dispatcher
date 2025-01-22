<?php

declare(strict_types=1);

namespace App\Module\Order\Repositories\Eloquent;

use App\Module\Order\Contracts\Repositories\CreateSenderRepository;
use App\Module\Order\Contracts\Repositories\UpdateSenderRepository;
use App\Module\Order\Models\Sender;
use Throwable;

final class SenderRepository implements CreateSenderRepository, UpdateSenderRepository
{
    /**
     * @throws Throwable
     */
    public function create(Sender $sender): void
    {
        $sender->saveOrFail();
    }

    /**
     * @throws Throwable
     */
    public function update(Sender $sender): void
    {
        $sender->saveOrFail();
    }
}
