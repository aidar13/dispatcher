<?php

declare(strict_types=1);

namespace App\Module\CourierApp\Contracts\Repositories\OrderTake;

use App\Module\CourierApp\DTO\IntegrationOneC\SetPackCodeOneCDTO;

interface SetInvoiceCargoPackCodeRepository
{
    public function setPackCode(SetPackCodeOneCDTO $DTO): string;
}
