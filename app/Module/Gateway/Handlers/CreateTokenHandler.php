<?php

declare(strict_types=1);

namespace App\Module\Gateway\Handlers;

use App\Module\Gateway\Commands\CreateTokenCommand;
use App\Module\Gateway\Contracts\TokenRepository;
use App\Module\Gateway\Model\Token;

final class CreateTokenHandler
{
    private TokenRepository $tokenRepository;

    public function __construct(TokenRepository $tokenRepository)
    {
        $this->tokenRepository = $tokenRepository;
    }

    public function handle(CreateTokenCommand $command): Token
    {
        $token                = new Token();
        $token->access_token  = $command->accessToken;
        $token->refresh_token = $command->refreshToken;
        $token->expires_in    = $command->expiresIn;
        $token->scope         = $command->scope;
        $token->adapter       = $command->adapter;

        $this->tokenRepository->save($token);

        return $token;
    }
}
