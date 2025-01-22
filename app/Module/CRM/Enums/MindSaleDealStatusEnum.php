<?php

namespace App\Module\CRM\Enums;

enum MindSaleDealStatusEnum: int
{
    case RAW                     = 1;
    case IN_PROGRESS             = 2;
    case NON_TARGETED            = 3;
    case REJECT                  = 4;
    case SALE                    = 5;
    case RE_TREATMENT            = 6;
    case IRRELEVANT_RE_TREATMENT = 7;
}
