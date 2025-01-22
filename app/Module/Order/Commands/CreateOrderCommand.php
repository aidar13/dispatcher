<?php

declare(strict_types=1);

namespace App\Module\Order\Commands;

use App\Module\Order\DTO\OrderDTO;
use Illuminate\Contracts\Queue\ShouldQueue;

final class CreateOrderCommand implements ShouldQueue
{
    public string $queue = 'dispatcherOrder';

    public function __construct(public OrderDTO $DTO)
    {
    }
}
