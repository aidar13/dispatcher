<?php

declare(strict_types=1);

namespace App\Module\Company\Queries\Eloquent;

use App\Module\Company\Contracts\Queries\CompanyQuery as CompanyQueryContract;
use App\Module\Company\Models\Company;
use Illuminate\Database\Eloquent\ModelNotFoundException;

final class CompanyQuery implements CompanyQueryContract
{
    public function findCompanyById(int $companyId): Company
    {
        /** @var Company $company */
        $company = Company::query()->find($companyId);

        if (!$company) {
            throw new ModelNotFoundException("Контрагент с id $companyId не найден!");
        }

        return $company;
    }
}
