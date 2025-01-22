<?php

declare(strict_types=1);

namespace App\Module\Gateway\Contracts;

use App\Module\Gateway\DTO\GatewayUserDTO;
use App\Module\Gateway\Models\GatewayUser;
use App\ValueObjects\PhoneNumber;
use Illuminate\Support\Collection;

interface GatewayUserQuery
{
    public function getUsersWithFilter(GatewayUserDTO $dto): ?Collection;

    public function find(int $id): GatewayUser;

    public function hasPhoneNumber(PhoneNumber $number): bool;
}
