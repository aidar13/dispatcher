<?php

declare(strict_types=1);

namespace App\Module\Status\Contracts\Services;

use App\Module\Status\DTO\StatusTypeIndexDTO;
use Illuminate\Pagination\LengthAwarePaginator;

interface StatusTypeService
{
    public function getAllStatusTypesPaginated(StatusTypeIndexDTO $DTO): LengthAwarePaginator;
}
