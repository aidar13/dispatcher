<?php

declare(strict_types=1);

namespace App\Module\Company\Contracts\Repositories;

use App\Module\Company\Models\Company;

interface UpdateCompanyRepository
{
    public function update(Company $company);
}
