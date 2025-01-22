<?php

declare(strict_types=1);

namespace App\Module\Company\Repositories\Decorators;

use App\Module\Company\Contracts\Repositories\CreateCompanyRepository;
use App\Module\Company\Models\Company;
use Illuminate\Support\Facades\Log;

final class CreateCompanyLogRepository implements CreateCompanyRepository
{
    public function __construct(
        private readonly CreateCompanyRepository $companyRepository
    ) {
    }

    public function create(Company $company)
    {
        Log::info('Создается контрагент', $company->getAttributes());

        $this->companyRepository->create($company);
    }
}
