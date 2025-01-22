<?php

declare(strict_types=1);

namespace App\Module\Order\Contracts\Repositories\Integration;

use App\Module\Take\DTO\ChangeTakeDateDTO;

interface ChangeInvoiceTakeDataRepository
{
    public function changeTakeDateByOrderInCabinet(ChangeTakeDateDTO $DTO): void;
}
