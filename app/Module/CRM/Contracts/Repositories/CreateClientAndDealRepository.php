<?php

declare(strict_types=1);

namespace App\Module\CRM\Contracts\Repositories;

use App\Module\CRM\DTO\Integration\CreateClientAndDealDTO;

interface CreateClientAndDealRepository
{
    public function createClientsDeals(CreateClientAndDealDTO $DTO): void;
}
