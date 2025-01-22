<?php

declare(strict_types=1);

namespace App\Module\Gateway\Contracts;

use App\Module\Gateway\Model\Token;

interface TokenQuery
{
    public function getTokenByAdapter(string $adapter): ?Token;

    public function findById(int $id): Token;
}
