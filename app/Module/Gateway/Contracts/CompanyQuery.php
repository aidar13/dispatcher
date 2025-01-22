<?php

declare(strict_types=1);

namespace App\Module\Gateway\Contracts;

interface CompanyQuery
{
    public function findById(int $id);
}
