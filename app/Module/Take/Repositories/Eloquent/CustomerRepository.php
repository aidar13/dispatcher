<?php

declare(strict_types=1);

namespace App\Module\Take\Repositories\Eloquent;

use App\Module\Take\Contracts\Repositories\CreateCustomerRepository;
use App\Module\Take\Contracts\Repositories\UpdateCustomerRepository;
use App\Module\Take\Models\Customer;
use Throwable;

final class CustomerRepository implements CreateCustomerRepository, UpdateCustomerRepository
{
    /**
     * @throws Throwable
     */
    public function create(Customer $customer): void
    {
        $customer->saveOrFail();
    }

    /**
     * @throws Throwable
     */
    public function update(Customer $customer): void
    {
        $customer->updateOrFail();
    }
}
