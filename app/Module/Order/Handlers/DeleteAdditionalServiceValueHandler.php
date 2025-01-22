<?php

declare(strict_types=1);

namespace App\Module\Order\Handlers;

use App\Module\Order\Commands\DeleteAdditionalServiceValueCommand;
use App\Module\Order\Contracts\Queries\AdditionalServiceValueQuery;
use App\Module\Order\Contracts\Repositories\DeleteAdditionalServiceValueRepository;

final readonly class DeleteAdditionalServiceValueHandler
{
    public function __construct(
        private AdditionalServiceValueQuery $additionalServiceValueQuery,
        private DeleteAdditionalServiceValueRepository $deleteAdditionalServiceValueRepository,
    ) {
    }

    public function handle(DeleteAdditionalServiceValueCommand $command): void
    {
        $additionalServiceValue = $this->additionalServiceValueQuery->findById($command->id);

        $this->deleteAdditionalServiceValueRepository->remove($additionalServiceValue);
    }
}
