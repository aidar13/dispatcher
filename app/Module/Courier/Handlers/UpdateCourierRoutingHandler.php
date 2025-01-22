<?php

declare(strict_types=1);

namespace App\Module\Courier\Handlers;

use App\Module\Courier\Commands\UpdateCourierRoutingCommand;
use App\Module\Courier\Contracts\Queries\CourierQuery;
use App\Module\Courier\Contracts\Repositories\UpdateCourierRepository;
use App\Module\Courier\Events\CourierUpdatedEvent;

final readonly class UpdateCourierRoutingHandler
{
    public function __construct(
        private CourierQuery $courierQuery,
        private UpdateCourierRepository $updateCourierRepository
    ) {
    }

    public function handle(UpdateCourierRoutingCommand $command): void
    {
        $courier = $this->courierQuery->getById($command->courierId);

        $courier->routing_enabled = $command->routingEnabled;

        $this->updateCourierRepository->update($courier);

        event(new CourierUpdatedEvent($courier->id));
    }
}
