<?php

declare(strict_types=1);

namespace App\Module\Order\Commands;

use App\Module\Order\DTO\AdditionalServiceValueDTO;

final readonly class CreateAdditionalServiceValueCommand
{
    public function __construct(public AdditionalServiceValueDTO $DTO)
    {
    }
}
