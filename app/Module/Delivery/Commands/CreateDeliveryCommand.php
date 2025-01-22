<?php

declare(strict_types=1);

namespace App\Module\Delivery\Commands;

use App\Module\Delivery\DTO\DeliveryDTO;
use Illuminate\Contracts\Queue\ShouldQueue;

final class CreateDeliveryCommand implements ShouldQueue
{
    public string $queue = 'dispatcherOrder';

    public function __construct(public readonly DeliveryDTO $DTO)
    {
    }
}
