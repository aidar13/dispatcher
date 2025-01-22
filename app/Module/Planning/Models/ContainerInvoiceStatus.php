<?php

declare(strict_types=1);

namespace App\Module\Planning\Models;

use Illuminate\Database\Eloquent\Model;

final class ContainerInvoiceStatus extends Model
{
    const ID_ASSEMBLED = 1;
    const TITLE_ASSEMBLED = 'Собран';
    const ID_PARTIALLY_ASSEMBLED = 2;
    const TITLE_PARTIALLY_ASSEMBLED = 'Частично собран';
}
