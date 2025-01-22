<?php

declare(strict_types=1);

namespace App\Module\Take\Handlers;

use App\Module\Order\Contracts\Queries\SenderQuery;
use App\Module\Take\Commands\UpdateCustomerSectorWithSenderIdCommand;
use App\Module\Take\Contracts\Queries\CustomerQuery;
use App\Module\Take\Contracts\Repositories\UpdateCustomerRepository;

final readonly class UpdateCustomerSectorWithSenderIdHandler
{
    public function __construct(
        private SenderQuery $senderQuery,
        private CustomerQuery $customerQuery,
        private UpdateCustomerRepository $repository,
    ) {
    }

    public function handle(UpdateCustomerSectorWithSenderIdCommand $command): void
    {
        $sender = $this->senderQuery->getById($command->senderId);

        $customers = $this->customerQuery->getAllBySenderId($sender->id);

        foreach ($customers as $customer) {
            $customer->sector_id            = $sender->sector_id;
            $customer->dispatcher_sector_id = $sender->dispatcher_sector_id;

            $this->repository->update($customer);
        }
    }
}
