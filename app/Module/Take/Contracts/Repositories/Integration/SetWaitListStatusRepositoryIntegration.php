<?php

namespace App\Module\Take\Contracts\Repositories\Integration;

interface SetWaitListStatusRepositoryIntegration
{
    public function setTakeWaitListStatusInCabinet(int $orderId, int $code, ?int $userId): void;
}
