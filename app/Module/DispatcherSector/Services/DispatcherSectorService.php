<?php

declare(strict_types=1);

namespace App\Module\DispatcherSector\Services;

use App\Module\DispatcherSector\Contracts\Queries\DispatcherSectorQuery;
use App\Module\DispatcherSector\Contracts\Services\DispatcherSectorService as DispatcherSectorServiceContract;
use App\Module\DispatcherSector\DTO\DispatcherSectorShowDTO;
use App\Module\Gateway\Contracts\GatewayUserQuery;
use App\Module\Gateway\DTO\GatewayUserDTO;
use App\Module\Gateway\Models\Role;
use App\Module\Gateway\Models\Status;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

final readonly class DispatcherSectorService implements DispatcherSectorServiceContract
{
    public function __construct(
        private DispatcherSectorQuery $query,
        private GatewayUserQuery $gatewayUserQuery,
    ) {
    }

    public function getAllDispatcherSectorsPaginated(DispatcherSectorShowDTO $DTO): LengthAwarePaginator
    {
        return $this->query->getAllPaginated($DTO);
    }

    public function getAllDispatcherSectorsActiveUsers(): Collection|array
    {
        $userIds = $this->getActiveDispatcherUserIds();

        return $this->query->getAllDispatcherSectorsActiveUsers($userIds);
    }

    private function getActiveDispatcherUserIds(): array
    {
        $DTO = new GatewayUserDTO();
        $DTO->setRoleId(Role::ID_DISPATCHER);
        $DTO->setStatusId(Status::ACTIVE);

        $users = $this->gatewayUserQuery->getUsersWithFilter($DTO);

        return $users?->pluck('id')->toArray();
    }
}
