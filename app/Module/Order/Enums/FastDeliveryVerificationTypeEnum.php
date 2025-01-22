<?php

namespace App\Module\Order\Enums;

enum FastDeliveryVerificationTypeEnum: int
{
    case SPARK_VERIFICATION_TYPE     = 1;
    case MERCHANT_VERIFICATION_TYPE  = 2;
}
