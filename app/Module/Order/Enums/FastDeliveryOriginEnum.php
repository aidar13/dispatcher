<?php

namespace App\Module\Order\Enums;

enum FastDeliveryOriginEnum: int
{
    case CLIENT      = 1;
    case DISPATCHER  = 2;
}
