<?php

declare(strict_types=1);

namespace App\Module\Status\Services;

use App\Module\Status\Contracts\Queries\StatusTypeQuery;
use App\Module\Status\Contracts\Services\StatusTypeService as StatusTypeServiceContract;
use App\Module\Status\DTO\StatusTypeIndexDTO;
use Illuminate\Pagination\LengthAwarePaginator;

final class StatusTypeService implements StatusTypeServiceContract
{
    public function __construct(
        public StatusTypeQuery $query
    ) {
    }

    public function getAllStatusTypesPaginated(StatusTypeIndexDTO $DTO): LengthAwarePaginator
    {
        return $this->query->getAllStatusTypesPaginated($DTO);
    }
}
