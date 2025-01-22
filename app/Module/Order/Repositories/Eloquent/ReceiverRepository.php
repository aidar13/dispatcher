<?php

declare(strict_types=1);

namespace App\Module\Order\Repositories\Eloquent;

use App\Module\Order\Contracts\Repositories\CreateReceiverRepository;
use App\Module\Order\Contracts\Repositories\UpdateReceiverRepository;
use App\Module\Order\Models\Receiver;

final class ReceiverRepository implements CreateReceiverRepository, UpdateReceiverRepository
{
    public function create(Receiver $receiver): void
    {
        $receiver->save();
    }

    /**
     * @throws \Throwable
     */
    public function update(Receiver $receiver): void
    {
        $receiver->saveOrFail();
    }
}
