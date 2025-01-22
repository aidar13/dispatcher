<?php

declare(strict_types=1);

namespace App\Module\Company\Commands;

use App\Module\Company\DTO\Integration\IntegrationCompanyDTO;

final class UpdateCompanyCommand
{
    public function __construct(
        public readonly IntegrationCompanyDTO $DTO
    ) {
    }
}
