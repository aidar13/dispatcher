<?php

declare(strict_types=1);

namespace App\Module\Status\Commands;

use App\Module\Status\DTO\SendOrderStatusDTO;
use Illuminate\Contracts\Queue\ShouldQueue;

final class SendOrderStatusToCabinetCommand implements ShouldQueue
{
    public function __construct(public SendOrderStatusDTO $DTO)
    {
    }
}
