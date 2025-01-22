<?php

declare(strict_types=1);

namespace App\Module\Inventory\Contracts\Repositories;

use App\Module\Inventory\DTO\Integration\IntegrationWriteOffDTO;

interface CreateWriteOffRepository
{
    public function create(IntegrationWriteOffDTO $DTO): void;
}
