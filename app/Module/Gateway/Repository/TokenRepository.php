<?php

declare(strict_types=1);

namespace App\Module\Gateway\Repository;

use App\Module\Gateway\Contracts\TokenRepository as TokenRepositoryContract;
use App\Module\Gateway\Model\Token;

final class TokenRepository implements TokenRepositoryContract
{
    public function save(Token $token)
    {
        $token->save();
    }
}
