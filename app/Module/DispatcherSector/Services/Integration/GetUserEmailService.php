<?php

declare(strict_types=1);

namespace App\Module\DispatcherSector\Services\Integration;

use App\Module\DispatcherSector\Contracts\Services\GetUserEmailService as GetUserEmailServiceContract;
use App\Module\Gateway\Contracts\GatewayUserQuery;
use App\Module\Gateway\DTO\GatewayUserDTO;
use App\Module\Gateway\Models\GatewayUser;
use App\Module\Gateway\Models\Role;
use Illuminate\Support\Collection;

final class GetUserEmailService implements GetUserEmailServiceContract
{
    public function __construct(
        private readonly GatewayUserQuery $gatewayUserQuery
    ) {
    }

    public function getDispatchers(array $userIds): Collection
    {
        $DTO = new GatewayUserDto();

        $DTO->setRoleIds([Role::ID_DISPATCHER]);
        $DTO->setIds($userIds);
        $DTO->setNeedLog(false);

        return $this->gatewayUserQuery->getUsersWithFilter($DTO)->filter()
            ->transform(fn (GatewayUser $user) => [
                'id'    => $user->id,
                'email' => $user->email
            ])->values();
    }
}
