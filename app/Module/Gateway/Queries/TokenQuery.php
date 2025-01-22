<?php

declare(strict_types=1);

namespace  App\Module\Gateway\Queries;

use App\Module\Gateway\Contracts\TokenQuery as TokenQueryContract;
use App\Module\Gateway\Model\Token;

final class TokenQuery implements TokenQueryContract
{
    public function getTokenByAdapter(string $adapter): ?Token
    {
        return Token::where('adapter', $adapter)->first();
    }

    public function findById(int $id): Token
    {
        return Token::findOrFail($id);
    }
}
