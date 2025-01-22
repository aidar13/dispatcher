<?php

declare(strict_types=1);

namespace App\Module\Take\Contracts\Repositories;

use App\Module\Take\Models\Customer;

interface CreateCustomerRepository
{
    public function create(Customer $customer): void;
}
