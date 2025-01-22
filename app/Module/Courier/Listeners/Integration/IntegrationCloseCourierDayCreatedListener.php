<?php

declare(strict_types=1);

namespace App\Module\Courier\Listeners\Integration;

use App\Module\Courier\Contracts\Queries\CloseCourierDayQuery;
use App\Module\Courier\DTO\Integration\CloseCourierDayDTO;
use App\Module\Courier\Events\Integration\IntegrationCloseCourierDayCreatedEvent;

final class IntegrationCloseCourierDayCreatedListener
{
    public function __construct(
        private readonly CloseCourierDayQuery $query
    ) {
    }

    public function handle($event): void
    {
        $closeCourier = $this->query->getById($event->closeCourierId);

        event(new IntegrationCloseCourierDayCreatedEvent(CloseCourierDayDTO::fromModel($closeCourier)));
    }
}
