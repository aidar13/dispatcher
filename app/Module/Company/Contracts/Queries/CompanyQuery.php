<?php

declare(strict_types=1);

namespace App\Module\Company\Contracts\Queries;

use App\Module\Company\Models\Company;

interface CompanyQuery
{
    public function findCompanyById(int $companyId): Company;
}
