<?php

declare(strict_types=1);

namespace App\Module\Gateway\Repositories;

use App\Module\Gateway\Contracts\TokenRepository as TokenRepositoryContract;
use App\Module\Gateway\Models\Token;

final class TokenRepository implements TokenRepositoryContract
{
    public function save(Token|\App\Module\Gateway\Model\Token $token): void
    {
        $token->save();
    }
}
