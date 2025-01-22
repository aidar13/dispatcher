<?php

declare(strict_types=1);

namespace Tests\Feature\Company;

use App\Module\Company\Commands\CreateCompanyCommand;
use App\Module\Company\Commands\UpdateCompanyCommand;
use App\Module\Company\DTO\Integration\IntegrationCompanyDTO;
use App\Module\Company\Models\Company;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Tests\TestCase;

final class CompanyTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;
    use WithoutMiddleware;

    public function setUp(): void
    {
        parent::setUp();
        $this->withoutExceptionHandling();
    }

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testCreateCompanyFromEvent()
    {
        $dto                 = new IntegrationCompanyDTO();
        $dto->id             = $this->faker->numberBetween();
        $dto->name           = $this->faker->company;
        $dto->shortName      = $this->faker->company;
        $dto->contactPhone   = $this->faker->numerify('77#########');
        $dto->contactName    = $this->faker->name;
        $dto->contactEmail   = $this->faker->email;
        $dto->contractNumber = (string)$this->faker->randomNumber();
        $dto->jurAddress     = $this->faker->address;
        $dto->factAddress    = $this->faker->address;
        $dto->bank           = $this->faker->text;
        $dto->bankAccount    = $this->faker->iban;
        $dto->bin            = $this->faker->text(12);
        $dto->bik            = $this->faker->swiftBicNumber;
        $dto->ndsNumber      = (string)$this->faker->randomNumber(7);
        $dto->code1c         = $this->faker->word();
        $dto->managerId      = $this->faker->randomNumber();


        dispatch(new CreateCompanyCommand($dto));

        $this->assertDatabaseHas('companies', [
            'id'              => $dto->id,
            'bin'             => $dto->bin,
            'name'            => $dto->name,
            'short_name'      => $dto->shortName,
            'contact_phone'   => $dto->contactPhone,
            'contact_name'    => $dto->contactName,
            'contact_email'   => $dto->contactEmail,
            'contract_number' => $dto->contractNumber,
            'jur_address'     => $dto->jurAddress,
            'fact_address'    => $dto->factAddress,
            'bank'            => $dto->bank,
            'bank_account'    => $dto->bankAccount,
            'bik'             => $dto->bik,
            'nds_number'      => $dto->ndsNumber,
            'code_1c'         => $dto->code1c,
            'manager_id'      => $dto->managerId,
        ]);
    }

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testUpdateCompanyFromEvent()
    {
        /** @var Company $company */
        $company = Company::factory()->create();

        $dto                 = new IntegrationCompanyDTO();
        $dto->id             = $company->id;
        $dto->name           = $this->faker->company;
        $dto->shortName      = $this->faker->company;
        $dto->contactPhone   = $this->faker->numerify('77#########');
        $dto->contactName    = $this->faker->name;
        $dto->contactEmail   = $this->faker->email;
        $dto->contractNumber = (string)$this->faker->randomNumber();
        $dto->jurAddress     = $this->faker->address;
        $dto->factAddress    = $this->faker->address;
        $dto->bank           = $this->faker->text;
        $dto->bankAccount    = $this->faker->iban;
        $dto->bin            = $this->faker->text(12);
        $dto->bik            = $this->faker->swiftBicNumber;
        $dto->ndsNumber      = (string)$this->faker->randomNumber(7);
        $dto->code1c         = $this->faker->word();
        $dto->managerId      = $this->faker->randomNumber();


        dispatch(new UpdateCompanyCommand($dto));

        $this->assertDatabaseHas('companies', [
            'id'              => $dto->id,
            'bin'             => $dto->bin,
            'name'            => $dto->name,
            'short_name'      => $dto->shortName,
            'contact_phone'   => $dto->contactPhone,
            'contact_name'    => $dto->contactName,
            'contact_email'   => $dto->contactEmail,
            'contract_number' => $dto->contractNumber,
            'jur_address'     => $dto->jurAddress,
            'fact_address'    => $dto->factAddress,
            'bank'            => $dto->bank,
            'bank_account'    => $dto->bankAccount,
            'bik'             => $dto->bik,
            'nds_number'      => $dto->ndsNumber,
            'code_1c'         => $dto->code1c,
            'manager_id'      => $dto->managerId,
        ]);
    }
}
