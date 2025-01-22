<?php

declare(strict_types=1);

namespace App\Module\Take\Queries;

use App\Module\Take\Contracts\Queries\CustomerQuery as CustomerQueryContract;
use App\Module\Take\Models\Customer;
use Illuminate\Database\Eloquent\Collection;

final class CustomerQuery implements CustomerQueryContract
{
    public function getById(int $id): ?Customer
    {
        /** @var Customer|null */
        return Customer::query()->where('id', $id)->first();
    }

    public function getAllBySenderId(int $senderId): Collection
    {
        /** @var Collection */
        return Customer::query()
            ->whereRelation('take.order', 'sender_id', $senderId)
            ->get();
    }

    public function getAllByReceiverId(int $receiverId): Collection
    {
        /** @var Collection */
        return Customer::query()
            ->whereRelation('delivery.invoice', 'receiver_id', $receiverId)
            ->get();
    }
}
