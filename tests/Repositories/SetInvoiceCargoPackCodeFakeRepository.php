<?php

declare(strict_types=1);

namespace Tests\Repositories;

use App\Module\CourierApp\Contracts\Repositories\OrderTake\SetInvoiceCargoPackCodeRepository;
use App\Module\CourierApp\DTO\IntegrationOneC\SetPackCodeOneCDTO;

final class SetInvoiceCargoPackCodeFakeRepository implements SetInvoiceCargoPackCodeRepository
{
    public function setPackCode(SetPackCodeOneCDTO $DTO): string
    {
        return 'P000000015';
    }
}
