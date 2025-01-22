<?php

namespace App\Http\Enums;

enum RequestSource: string
{
    case BPMS        = 'BPMS';
    case ARM         = 'ARM';
    case COURIER_APP = 'МПК';
}
