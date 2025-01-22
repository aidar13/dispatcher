<?php

declare(strict_types=1);

namespace App\Module\Delivery\Handlers;

use App\Module\Delivery\Commands\UpdateCustomerSectorWithReceiverIdCommand;
use App\Module\Order\Contracts\Queries\ReceiverQuery;
use App\Module\Take\Contracts\Queries\CustomerQuery;
use App\Module\Take\Contracts\Repositories\UpdateCustomerRepository;

final readonly class UpdateCustomerSectorWithReceiverIdHandler
{
    public function __construct(
        private ReceiverQuery $receiverQuery,
        private CustomerQuery $customerQuery,
        private UpdateCustomerRepository $repository,
    ) {
    }

    public function handle(UpdateCustomerSectorWithReceiverIdCommand $command): void
    {
        $receiver = $this->receiverQuery->getById($command->senderId);

        $customers = $this->customerQuery->getAllByReceiverId($receiver->id);

        foreach ($customers as $customer) {
            $customer->sector_id            = $receiver->sector_id;
            $customer->dispatcher_sector_id = $receiver->dispatcher_sector_id;

            $this->repository->update($customer);
        }
    }
}
