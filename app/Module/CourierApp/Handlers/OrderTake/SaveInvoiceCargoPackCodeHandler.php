<?php

declare(strict_types=1);

namespace App\Module\CourierApp\Handlers\OrderTake;

use App\Module\CourierApp\Commands\OrderTake\SaveInvoiceCargoPackCodeCommand;
use App\Module\CourierApp\Contracts\Repositories\OrderTake\SetInvoiceCargoPackCodeRepository;
use App\Module\CourierApp\DTO\IntegrationOneC\SetPackCodeOneCDTO;
use App\Module\CourierApp\Events\OrderTake\InvoiceCargoSizeTypeSetEvent;
use App\Module\Order\Contracts\Queries\InvoiceCargoQuery;
use App\Module\Order\Contracts\Repositories\UpdateInvoiceCargoRepository;

final class SaveInvoiceCargoPackCodeHandler
{
    public function __construct(
        private readonly InvoiceCargoQuery $query,
        private readonly UpdateInvoiceCargoRepository $repository,
        private readonly SetInvoiceCargoPackCodeRepository $packCodeRepository
    ) {
    }

    public function handle(SaveInvoiceCargoPackCodeCommand $command): void
    {
        $invoiceCargo = $this->query->getByInvoiceId(
            $command->invoiceId,
            ['id', 'invoice_id'],
            ['invoice']
        );

        if (!$invoiceCargo) {
            return;
        }

        $sizeType = $this->getSizeType($invoiceCargo->invoice->invoice_number, $command->packCode);

        $invoiceCargo->size_type = $sizeType;
        $invoiceCargo->pack_code = $command->packCode;
        $this->repository->update($invoiceCargo);

        event(new InvoiceCargoSizeTypeSetEvent($invoiceCargo->id));
    }

    private function getSizeType(string $invoiceNumber, string $packCode): string
    {
        $DTO = new SetPackCodeOneCDTO();
        $DTO->setPackCode($packCode);
        $DTO->setInvoiceNumber($invoiceNumber);

        return $this->packCodeRepository->setPackCode($DTO);
    }
}
