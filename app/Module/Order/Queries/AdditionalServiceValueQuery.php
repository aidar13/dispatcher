<?php

declare(strict_types=1);

namespace App\Module\Order\Queries;

use App\Module\Order\Contracts\Queries\AdditionalServiceValueQuery as AdditionalServiceValueQueryContract;
use App\Module\Order\Models\AdditionalServiceValue;

final class AdditionalServiceValueQuery implements AdditionalServiceValueQueryContract
{
    public function getById(int $id, array $columns = ['*'], array $relations = []): ?AdditionalServiceValue
    {
        /** @var AdditionalServiceValue|null */
        return AdditionalServiceValue::query()
            ->select($columns)
            ->with($relations)
            ->find($id);
    }

    public function findById(int $id): AdditionalServiceValue
    {
        return AdditionalServiceValue::findOrFail($id);
    }
}
