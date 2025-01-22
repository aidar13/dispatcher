<?php

declare(strict_types=1);

namespace App\Module\Company\Listeners;

use App\Module\Company\Commands\CreateCompanyCommand;
use App\Module\Company\DTO\Integration\IntegrationCompanyDTO;

final class CreateCompanyListener
{
    public function handle($event)
    {
        dispatch(new CreateCompanyCommand(IntegrationCompanyDTO::fromEvent($event)));
    }
}
