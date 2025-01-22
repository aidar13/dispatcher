<?php

declare(strict_types=1);

namespace App\Module\Courier\Events\Integration;

use App\Module\Courier\DTO\Integration\CloseCourierDayDTO;
use Ludovicose\TransactionOutbox\Contracts\ShouldBePublish;

final class IntegrationCloseCourierDayCreatedEvent implements ShouldBePublish
{
    public function __construct(public readonly CloseCourierDayDTO $DTO)
    {
    }

    public function getChannel(): string
    {
        return 'courier.close-day.created';
    }
}
