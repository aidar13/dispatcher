<?php

declare(strict_types=1);

namespace App\Module\OneC\DTO\Integration;

class Integration1CConfigDTO
{
    public function __construct(
        public readonly string $uri,
        public readonly string $login,
        public readonly string $password,
        public readonly string $token
    ) {
    }
}
