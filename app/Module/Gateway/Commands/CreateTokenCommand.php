<?php

declare(strict_types=1);

namespace App\Module\Gateway\Commands;

final class CreateTokenCommand
{
    public string $accessToken;
    public string $refreshToken;
    public string $expiresIn;
    public ?string $scope;
    public string $adapter;

    public function __construct(string $adapter, string $accessToken, string $refreshToken, string $expiresIn, ?string $scope = null)
    {
        $this->accessToken  = $accessToken;
        $this->refreshToken = $refreshToken;
        $this->expiresIn    = $expiresIn;
        $this->scope        = $scope;
        $this->adapter      = $adapter;
    }
}
