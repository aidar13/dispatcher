<?php

declare(strict_types=1);

namespace App\Module\Gateway\Repository;

use App\Module\Gateway\Commands\CreateTokenCommand;
use App\Module\Gateway\Commands\UpdateTokenCommand;
use App\Module\Gateway\Contracts\AuthRepository;
use App\Module\Gateway\Contracts\TokenQuery;
use Illuminate\Support\Carbon;

abstract class BaseAuthRepository implements AuthRepository
{
    public string $adapter = '';

    private TokenQuery $tokenQuery;

    public function __construct()
    {
        $this->tokenQuery = app()->make(TokenQuery::class);
    }

    public function auth(): string
    {
        $adapter = $this->tokenQuery->getTokenByAdapter($this->adapter);

        if (!$adapter) {
            $tokenInfo = $this->authByPassword();
            dispatch_sync(new CreateTokenCommand($this->adapter, $tokenInfo['access_token'], $tokenInfo['refresh_token'], Carbon::now()->addSeconds($tokenInfo['expires_in'])->toDateString()));
            return $tokenInfo['access_token'];
        }

        $expiresInDate = Carbon::createFromDate($adapter->expires_in);

        if ($expiresInDate->gte(now())) {
            return $adapter->access_token;
        }

        try {
            $tokenInfo = $this->authByRefreshToken($adapter->refresh_token);
        } catch (\Exception) {
            $tokenInfo = $this->authByPassword();
        }

        dispatch_sync(new UpdateTokenCommand($adapter->id, $this->adapter, $tokenInfo['access_token'], $tokenInfo['refresh_token'], Carbon::now()->addSeconds($tokenInfo['expires_in'])->toDateString()));

        return $tokenInfo['access_token'];
    }

    abstract public function authByRefreshToken(string $refreshToken): array;

    abstract public function authByPassword(): array;
}
