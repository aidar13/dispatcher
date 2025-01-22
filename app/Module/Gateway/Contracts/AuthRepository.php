<?php

declare(strict_types=1);

namespace App\Module\Gateway\Contracts;

interface AuthRepository
{
    public function auth(): string;
}
