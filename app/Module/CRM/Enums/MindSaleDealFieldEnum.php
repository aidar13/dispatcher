<?php

namespace App\Module\CRM\Enums;

enum MindSaleDealFieldEnum: int
{
    case BUDGET                 = 2;
    case DATE_OF_SCHEDULED_DEAL = 3;
    case ORDER_INVOICE_NUMBER   = 9;
    case COURIER_FULL_NAME      = 10;
    case COMMENT                = 11;
    case CLIENT_FULL_NAME       = 12;
    case RECEIPT_DATE           = 17;
}
