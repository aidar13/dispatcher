<?php

declare(strict_types=1);

namespace App\Module\Company\Repositories;

use App\Module\Company\Contracts\Repositories\CreateCompanyRepository;
use App\Module\Company\Contracts\Repositories\UpdateCompanyRepository;
use App\Module\Company\Models\Company;
use Throwable;

final class CompanyRepository implements CreateCompanyRepository, UpdateCompanyRepository
{
    /**
     * @throws Throwable
     */
    public function create(Company $company)
    {
        $company->saveOrFail();
    }

    /**
     * @throws Throwable
     */
    public function update(Company $company)
    {
        $company->saveOrFail();
    }
}
