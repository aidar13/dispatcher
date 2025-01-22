<?php

declare(strict_types=1);

namespace App\Module\Gateway\Repository;

use Illuminate\Support\Facades\Http;

final class GatewayAuthRepository extends BaseAuthRepository
{
    private string $url;
    private string $id;
    private string $secret;
    private string $path = '/oauth/token';

    public function __construct()
    {
        $this->url     = config('gateway.url');
        $this->id      = config('gateway.clientId');
        $this->secret  = config('gateway.clientSecret');
        $this->adapter = 'gateway';

        parent::__construct();
    }

    public function authByRefreshToken(string $refreshToken): array
    {
        $response = Http::asForm()->post($this->url . $this->path, [
            'grant_type'    => 'refresh_token',
            'refresh_token' => $refreshToken,
            'client_id'     => $this->id,
            'client_secret' => $this->secret,
        ]);

        if ($response->failed()) {
            throw new \DomainException('Не смог авторизоваться на Gateway через токен');
        }

        return $response->json();
    }

    public function authByPassword(): array
    {
        $response = Http::asForm()->post($this->url . $this->path, [
            'grant_type'    => 'service',
            'client_id'     => $this->id,
            'client_secret' => $this->secret,
        ]);

        if ($response->failed()) {
            throw new \DomainException('Не смог авторизоваться на Gateway через пароль');
        }

        return $response->json();
    }
}
