<?php

declare(strict_types=1);

namespace App\Module\Gateway\Commands;

final class UpdateTokenCommand
{
    public string $accessToken;
    public string $refreshToken;
    public string $expiresIn;
    public ?string $scope;
    public string $adapter;
    public int $id;

    public function __construct(int $id, string $adapter, string $accessToken, string $refreshToken, string $expiresIn, ?string $scope = null)
    {
        $this->accessToken  = $accessToken;
        $this->refreshToken = $refreshToken;
        $this->expiresIn    = $expiresIn;
        $this->scope        = $scope;
        $this->adapter      = $adapter;
        $this->id           = $id;
    }
}
