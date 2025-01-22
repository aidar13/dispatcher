<?php

declare(strict_types=1);

namespace Tests\Repositories;

use App\Module\Gateway\Contracts\AuthRepository;

final class AuthFakerRepository implements AuthRepository
{
    /**
     * @return string
     */
    public function auth(): string
    {
        return 'token';
    }
}
