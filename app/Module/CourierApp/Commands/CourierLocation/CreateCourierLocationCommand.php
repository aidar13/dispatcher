<?php

declare(strict_types=1);

namespace App\Module\CourierApp\Commands\CourierLocation;

use App\Module\CourierApp\DTO\CourierLocation\CreateCourierLocationDTO;
use Illuminate\Contracts\Queue\ShouldQueue;

final readonly class CreateCourierLocationCommand implements ShouldQueue
{
    public string $queue;

    public function __construct(
        public int $userId,
        public CreateCourierLocationDTO $DTO,
    ) {
        $this->queue = 'courierLocation';
    }
}
