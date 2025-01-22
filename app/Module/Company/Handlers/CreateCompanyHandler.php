<?php

declare(strict_types=1);

namespace App\Module\Company\Handlers;

use App\Module\Company\Commands\CreateCompanyCommand;
use App\Module\Company\Contracts\Queries\CompanyQuery;
use App\Module\Company\Contracts\Repositories\CreateCompanyRepository;
use App\Module\Company\Models\Company;
use Illuminate\Database\Eloquent\ModelNotFoundException;

final class CreateCompanyHandler
{
    public function __construct(
        private readonly CreateCompanyRepository $companyRepository,
        private readonly CompanyQuery $companyQuery
    ) {
    }

    public function handle(CreateCompanyCommand $command)
    {
        if ($this->doesCompanyExist($command->DTO->id)) {
            return;
        }

        $company = new Company();
        $company->setId($command->DTO->id);
        $company->setName($command->DTO->name);
        $company->setShortName($command->DTO->shortName);
        $company->setBin($command->DTO->bin);
        $company->setContactPhone($command->DTO->contactPhone);
        $company->setContactEmail($command->DTO->contactEmail);
        $company->setContactName($command->DTO->contactName);
        $company->setJurAddress($command->DTO->jurAddress);
        $company->setFactAddress($command->DTO->factAddress);
        $company->setNdsNumber($command->DTO->ndsNumber);
        $company->setBank($command->DTO->bank);
        $company->setBik($command->DTO->bik);
        $company->setBankAccount($command->DTO->bankAccount);
        $company->setCode1c($command->DTO->code1c);
        $company->setContractNumber($command->DTO->contractNumber);
        $company->setManagerId($command->DTO->managerId);

        $this->companyRepository->create($company);
    }


    private function doesCompanyExist(int $companyId): bool
    {
        try {
            $this->companyQuery->findCompanyById($companyId);
            return true;
        } catch (ModelNotFoundException $exception) {
            return false;
        }
    }
}
