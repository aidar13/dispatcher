<?php

declare(strict_types=1);

namespace App\Module\Order\Contracts\Queries;

use App\Module\Order\Models\AdditionalServiceValue;

interface AdditionalServiceValueQuery
{
    public function getById(int $id, array $columns = ['*'], array $relations = []): ?AdditionalServiceValue;

    public function findById(int $id): AdditionalServiceValue;
}
