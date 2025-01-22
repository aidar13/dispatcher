<?php

declare(strict_types=1);

namespace App\Module\Order\Commands;

use App\Module\Order\DTO\ReceiverDTO;
use Illuminate\Contracts\Queue\ShouldQueue;

final class UpdateReceiverCommand implements ShouldQueue
{
    public string $queue = 'dispatcherOrder';

    public function __construct(public ReceiverDTO $DTO)
    {
    }
}
