<?php

declare(strict_types=1);

namespace App\Module\DispatcherSector\Contracts\Integrations\Repositories;

use App\Module\DispatcherSector\DTO\Integration\IntegrationSector1CDTO;

interface SendSectorTo1CRepository
{
    public function sendSectorTo1C(IntegrationSector1CDTO $DTO): void;
}
