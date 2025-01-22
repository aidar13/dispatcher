<?php

declare(strict_types=1);

namespace App\Module\Company\Contracts\Repositories;

use App\Module\Company\Models\Company;

interface CreateCompanyRepository
{
    public function create(Company $company);
}
