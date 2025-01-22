<?php

namespace App\Module\Gateway\Event;

use App\Module\Gateway\DTO\SendEmailDTO;
use Ludovicose\TransactionOutbox\Contracts\ShouldBePublish;

class EmailCreatedEvent implements ShouldBePublish
{
    public function __construct(public SendEmailDTO $DTO)
    {
    }

    public function getChannel(): string
    {
        return 'email.created';
    }
}
