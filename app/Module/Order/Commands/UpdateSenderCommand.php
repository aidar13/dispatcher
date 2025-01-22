<?php

declare(strict_types=1);

namespace App\Module\Order\Commands;

use App\Module\Order\DTO\SenderDTO;
use Illuminate\Contracts\Queue\ShouldQueue;

final class UpdateSenderCommand implements ShouldQueue
{
    public string $queue = 'dispatcherOrder';

    public function __construct(public SenderDTO $DTO)
    {
    }
}
