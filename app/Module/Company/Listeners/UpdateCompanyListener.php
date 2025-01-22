<?php

declare(strict_types=1);

namespace App\Module\Company\Listeners;

use App\Module\Company\Commands\UpdateCompanyCommand;
use App\Module\Company\DTO\Integration\IntegrationCompanyDTO;

final class UpdateCompanyListener
{
    public function handle($event)
    {
        dispatch(new UpdateCompanyCommand(IntegrationCompanyDTO::fromEvent($event)));
    }
}
