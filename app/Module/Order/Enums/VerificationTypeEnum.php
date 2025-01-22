<?php

declare(strict_types=1);

namespace App\Module\Order\Enums;

enum VerificationTypeEnum: int
{
    case SPARK_VERIFICATION_TYPE_ID = 1;
    case KASPI_VERIFICATION_TYPE_ID = 2;
}
