<?php

declare(strict_types=1);

namespace App\Module\Status\Contracts\Queries;

use App\Module\Status\DTO\StatusTypeIndexDTO;
use Illuminate\Pagination\LengthAwarePaginator;

interface StatusTypeQuery
{
    public function getAllStatusTypesPaginated(StatusTypeIndexDTO $DTO): LengthAwarePaginator;
}
