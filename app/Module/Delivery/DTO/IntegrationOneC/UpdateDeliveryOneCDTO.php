<?php

declare(strict_types=1);

namespace App\Module\Delivery\DTO\IntegrationOneC;

use App\Module\Delivery\Models\Delivery;
use App\Module\Order\Models\Invoice;
use App\Module\Status\Models\RefStatus;
use Illuminate\Support\Carbon;

final class UpdateDeliveryOneCDTO
{
    public int $courierId;
    public string $invoiceNumber;
    public ?string $courierComment;
    public ?string $deliveryReceiverName;
    public ?string $status;
    public string $deliveredAt;
    public ?int $waitListStatusCode;
    public bool $isPickup;

    public function toArray(): array
    {
        return [
            'КодКурьера'     => $this->courierId,
            'НомерНакладной' => $this->invoiceNumber,
            'Комментарий'    => $this->courierComment,
            'ГрузПринял'     => $this->deliveryReceiverName,
            'Статус'         => $this->status,
            'КурьерскаяДата' => $this->deliveredAt,
            'ЛистОжидания'   => $this->waitListStatusCode,
            'Самовывоз'      => $this->isPickup,
        ];
    }

    public static function fromModel(Delivery $delivery): self
    {
        $self = new self();

        $self->setCourierId($delivery->courier_id);
        $self->setInvoiceNumber($delivery->invoice_number);
        $self->setCourierComment($delivery->courier_comment);
        $self->setDeliveryReceiverName($delivery->delivery_receiver_name);
        $self->setStatus($delivery->getStatusForOneC());
        $self->setDeliveredAt((new Carbon($delivery->delivered_at))->format('Y-m-d\TH:i:s'));
        $self->setWaitListStatusCode($self->getWaitListStatusCode($delivery));
        $self->setIsPickup($delivery->invoice->receiver->isPickup());

        return $self;
    }

    public static function fromInvoiceModel(Invoice $invoice): self
    {
        $self = new self();

        $self->setCourierId($invoice->receiver->dispatcherSector->courier_id);
        $self->setInvoiceNumber($invoice->invoice_number);
        $self->setCourierComment($invoice->container?->fastDeliveryOrder?->getProviderName());
        $self->setDeliveryReceiverName($invoice->receiver->full_name);
        $self->setStatus((string)Delivery::STATUS_DELIVERED);
        $self->setDeliveredAt((new Carbon($invoice->getStatusByCode(RefStatus::CODE_DELIVERED)->created_at))->format('Y-m-d\TH:i:s'));
        $self->setWaitListStatusCode(null);
        $self->setIsPickup($invoice->receiver->isPickup());

        return $self;
    }

    public function setCourierId(int $courierId): void
    {
        $this->courierId = $courierId;
    }

    public function setInvoiceNumber(string $invoiceNumber): void
    {
        $this->invoiceNumber = $invoiceNumber;
    }

    public function setCourierComment(?string $courierComment): void
    {
        $this->courierComment = $courierComment;
    }

    public function setDeliveryReceiverName(?string $deliveryReceiverName): void
    {
        $this->deliveryReceiverName = $deliveryReceiverName;
    }

    public function setStatus(?string $status): void
    {
        $this->status = $status;
    }

    public function setDeliveredAt(string $deliveredAt): void
    {
        $this->deliveredAt = $deliveredAt;
    }

    public function setWaitListStatusCode(?int $waitListStatusCode): void
    {
        $this->waitListStatusCode = $waitListStatusCode;
    }

    public function setIsPickup(bool $isPickup): void
    {
        $this->isPickup = $isPickup;
    }

    private function getWaitListStatusCode(Delivery $delivery): ?int
    {
        if ($delivery->isDelivered()) {
            return null;
        }

        return $delivery->waitListStatus?->code;
    }
}
