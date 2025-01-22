<?php

declare(strict_types=1);

namespace App\Module\Order\DTO\Integration;

use App\Module\DispatcherSector\DTO\WarehouseDTO;
use App\Module\Planning\Models\Container;
use Illuminate\Support\Collection;

final class CreateFastDeliveryOrderDTO
{
    public int $cityId;
    public int $containerId;
    public string $containerName;
    public int $courierId;
    public WarehouseDTO $senderDTO;
    public Collection $receivers;
    private ?int $providerId;

    /**
     * @psalm-suppress InvalidArgument
     */
    public static function fromContainerAndWarehouse(Container $container, WarehouseDTO $warehouseDTO): self
    {
        $self                = new self();
        $self->containerId   = $container->id;
        $self->containerName = $container->title;
        $self->courierId     = $container->courier_id;
        $self->providerId    = $container->fastDeliveryOrder?->type ?? null;
        $self->cityId        = $warehouseDTO->cityId;
        $self->senderDTO     = $warehouseDTO;
        $self->receivers     = collect();

        foreach ($container->invoices as $invoice) {
            $receiverDTO = ReceiverDTO::fromModel($invoice->receiver, $invoice);
            $self->receivers->push($receiverDTO);
        }

        return $self;
    }

    public function getRequestPayload(): array
    {
        return [
            'title'        => $this->senderDTO->title,
            'order_id'     => $this->senderDTO->id,
            'city_id'      => $this->cityId,
            'type_id'      => 2,
            'provider_id'  => $this->providerId,
            'shouldReturn' => true,
            'container'    => [
                'id'        => $this->containerId,
                'name'      => $this->containerName,
                'courierId' => $this->courierId
            ],
            'sender'       => [
                'city_id'      => $this->senderDTO->cityId,
                'full_name'    => $this->senderDTO->fullName,
                'phone_number' => $this->senderDTO->phone,
                'house'        => $this->senderDTO->house,
                'street'       => $this->senderDTO->street,
                'full_address' => $this->senderDTO->fullAddress,
                'longitude'    => $this->senderDTO->longitude,
                'latitude'     => $this->senderDTO->latitude
            ],
            'receivers'    => $this->receivers->map(function (ReceiverDTO $DTO) {
                return [
                    'id'               => $DTO->receiverId,
                    'full_name'        => $DTO->fullName,
                    'city_id'          => $this->cityId,
                    'phone_number'     => $DTO->phone,
                    'additional_phone' => $DTO->additionalPhone,
                    'house'            => $DTO->house,
                    'street'           => $DTO->street,
                    'full_address'     => $DTO->fullAddress,
                    'longitude'        => $DTO->longitude,
                    'latitude'         => $DTO->latitude,
                    'invoice_number'   => $DTO->invoiceNumber,
                    'verify'           => $DTO->verify,
                    'cargo'            => $DTO->cargo,
                ];
            })->toArray()
        ];
    }
}
