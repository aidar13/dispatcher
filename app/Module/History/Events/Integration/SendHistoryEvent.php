<?php

declare(strict_types=1);

namespace App\Module\History\Events\Integration;

use App\Module\History\DTO\SendHistoryDTO;
use Ludovicose\TransactionOutbox\Contracts\ShouldBePublish;

final class SendHistoryEvent implements ShouldBePublish
{
    public function __construct(public SendHistoryDTO $DTO)
    {
    }

    public function getChannel(): string
    {
        return 'history.create';
    }
}
