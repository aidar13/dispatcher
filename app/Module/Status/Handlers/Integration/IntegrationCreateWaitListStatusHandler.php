<?php

declare(strict_types=1);

namespace App\Module\Status\Handlers\Integration;

use App\Module\Gateway\Contracts\Integration\HttpClientRequest;
use App\Module\Status\Commands\Integration\IntegrationCreateWaitListStatusCommand;

final readonly class IntegrationCreateWaitListStatusHandler
{
    public function __construct(
        private HttpClientRequest $client,
    ) {
    }

    public function handle(IntegrationCreateWaitListStatusCommand $command): void
    {
        $this->client->makeRequest(
            'POST',
            '/cabinet/api/wait-list-statuses',
            $command->DTO->toArray(),
        );
    }
}
