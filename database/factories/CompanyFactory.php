<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Module\Company\Models\Company;
use Illuminate\Database\Eloquent\Factories\Factory;

final class CompanyFactory extends Factory
{
    protected $model = Company::class;

    public function definition(): array
    {
        return [
            'name'            => $this->faker->company,
            'bin'             => $this->faker->text(12),
            'contact_name'    => $this->faker->text,
            'contact_phone'   => $this->faker->phoneNumber,
            'contact_email'   => $this->faker->email,
            'jur_address'     => $this->faker->address,
            'fact_address'    => $this->faker->address,
            'nds_number'      => $this->faker->text,
            'bank'            => $this->faker->text,
            'bik'             => $this->faker->text,
            'bank_account'    => $this->faker->iban,
            'code_1c'         => $this->faker->text(),
            'contract_number' => $this->faker->text,
            'manager_id'      => $this->faker->randomNumber(),
        ];
    }
}
