<?php

declare(strict_types=1);

namespace App\Module\Gateway\Handlers;

use App\Module\Gateway\Commands\UpdateTokenCommand;
use App\Module\Gateway\Contracts\TokenQuery;
use App\Module\Gateway\Contracts\TokenRepository;
use App\Module\Gateway\Model\Token;

final class UpdateTokenHandler
{
    private TokenRepository $tokenRepository;
    private TokenQuery $tokenQuery;

    public function __construct(TokenQuery $tokenQuery, TokenRepository $tokenRepository)
    {
        $this->tokenRepository = $tokenRepository;
        $this->tokenQuery      = $tokenQuery;
    }

    public function handle(UpdateTokenCommand $command): Token
    {
        $token                = $this->tokenQuery->findById($command->id);
        $token->access_token  = $command->accessToken;
        $token->refresh_token = $command->refreshToken;
        $token->expires_in    = $command->expiresIn;
        $token->scope         = $command->scope;
        $token->adapter       = $command->adapter;
        $this->tokenRepository->save($token);

        return $token;
    }
}
