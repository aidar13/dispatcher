<?php

namespace App\Module\Gateway\DTO;

use App\Constants\PageConstants;

final class GatewayUserDTO
{
    public ?array $ids     = null;
    public ?string $email  = null;
    public ?int $roleId    = null;
    public ?int $statusId  = null;
    public ?array $roleIds = null;
    public int $isExtended = 0;
    public bool $needLog   = true;
    public int $limit      = PageConstants::LIMIT_FOR_PAGE;

    public function setIds(?array $ids): void
    {
        $this->ids = $ids;
    }

    public function setEmail(?string $email): void
    {
        $this->email = $email;
    }

    public function setRoleId(?int $roleId): void
    {
        $this->roleId = $roleId;
    }

    public function setIsExtended(int $isExtended): void
    {
        $this->isExtended = $isExtended;
    }

    public function setRoleIds(?array $roleIds): void
    {
        $this->roleIds = $roleIds;
    }

    public function setNeedLog(bool $needLog): void
    {
        $this->needLog = $needLog;
    }

    public function setStatusId(?int $statusId): void
    {
        $this->statusId = $statusId;
    }
}
