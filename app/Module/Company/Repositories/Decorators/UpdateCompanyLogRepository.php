<?php

declare(strict_types=1);

namespace App\Module\Company\Repositories\Decorators;

use App\Module\Company\Contracts\Repositories\UpdateCompanyRepository;
use App\Module\Company\Models\Company;
use Illuminate\Support\Facades\Log;

final class UpdateCompanyLogRepository implements UpdateCompanyRepository
{
    public function __construct(
        private readonly UpdateCompanyRepository $companyRepository
    ) {
    }

    public function update(Company $company)
    {
        Log::info(
            'Данные по контрагенту обновляются ' . $company->id,
            $company->getDirty()
        );

        $this->companyRepository->update($company);
    }
}
