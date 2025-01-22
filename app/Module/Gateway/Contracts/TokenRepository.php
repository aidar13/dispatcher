<?php

declare(strict_types=1);

namespace App\Module\Gateway\Contracts;

use App\Module\Gateway\Model\Token;

interface TokenRepository
{
    public function save(Token $token);
}
