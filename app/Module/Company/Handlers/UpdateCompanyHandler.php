<?php

declare(strict_types=1);

namespace App\Module\Company\Handlers;

use App\Module\Company\Commands\UpdateCompanyCommand;
use App\Module\Company\Contracts\Queries\CompanyQuery;
use App\Module\Company\Contracts\Repositories\UpdateCompanyRepository;

final class UpdateCompanyHandler
{
    public function __construct(
        private readonly CompanyQuery $companyQuery,
        private readonly UpdateCompanyRepository $companyRepository
    ) {
    }

    public function handle(UpdateCompanyCommand $command)
    {
        $company = $this->companyQuery->findCompanyById($command->DTO->id);

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

        $this->companyRepository->update($company);
    }
}
