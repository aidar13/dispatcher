<?php

declare(strict_types=1);

namespace App\Module\Status\Queries;

use App\Module\Status\Contracts\Queries\StatusTypeQuery as StatusTypeQueryContract;
use App\Module\Status\DTO\StatusTypeIndexDTO;
use App\Module\Status\Models\StatusType;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Pagination\LengthAwarePaginator;

final class StatusTypeQuery implements StatusTypeQueryContract
{
    public function getAllStatusTypesPaginated(StatusTypeIndexDTO $DTO): LengthAwarePaginator
    {
        return StatusType::when($DTO->typeId, fn(Builder $query) => $query->where('type', $DTO->typeId))
            ->paginate($DTO->limit, ['*'], 'page', $DTO->page);
    }
}
