<?php

declare(strict_types=1);

namespace App\Module\Take\Contracts\Repositories\Integration;

use App\Module\Status\DTO\SendOrderStatusDTO;

interface IntegrationOrderStatusRepository
{
    public function sendStatusToCabinet(SendOrderStatusDTO $DTO): void;
}
