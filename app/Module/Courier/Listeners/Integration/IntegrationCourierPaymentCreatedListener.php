<?php

declare(strict_types=1);

namespace App\Module\Courier\Listeners\Integration;

use App\Module\Courier\Commands\Integration\CreateCourierPaymentCommand;
use App\Module\Courier\DTO\CourierPaymentDTO;

final class IntegrationCourierPaymentCreatedListener
{
    public function handle($event): void
    {
        dispatch(new CreateCourierPaymentCommand(CourierPaymentDTO::fromEvent($event)));
    }
}
