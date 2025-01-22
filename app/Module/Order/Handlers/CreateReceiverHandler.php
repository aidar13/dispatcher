<?php

declare(strict_types=1);

namespace App\Module\Order\Handlers;

use App\Module\DispatcherSector\Contracts\Queries\DispatcherSectorPolygonQuery;
use App\Module\DispatcherSector\Contracts\Queries\SectorPolygonQuery;
use App\Module\Order\Commands\CreateReceiverCommand;
use App\Module\Order\Contracts\Queries\ReceiverQuery;
use App\Module\Order\Contracts\Repositories\CreateReceiverRepository;
use App\Module\Order\Models\Receiver;

final class CreateReceiverHandler
{
    public function __construct(
        private readonly ReceiverQuery $receiverQuery,
        private readonly CreateReceiverRepository $createReceiverRepository,
        private readonly SectorPolygonQuery $sectorQuery,
        private readonly DispatcherSectorPolygonQuery $dispatcherSectorQuery
    ) {
    }

    public function handle(CreateReceiverCommand $command): void
    {
        if ($this->hasReceiver($command->DTO->id)) {
            return;
        }

        $sector           = $this->sectorQuery->findByCoordinates($command->DTO->latitude, $command->DTO->longitude);
        $dispatcherSector = $this->dispatcherSectorQuery->findByCoordinates($command->DTO->latitude, $command->DTO->longitude);

        $receiver                       = new Receiver();
        $receiver->id                   = $command->DTO->id;
        $receiver->city_id              = $command->DTO->cityId;
        $receiver->full_address         = $command->DTO->fullAddress;
        $receiver->title                = $command->DTO->title;
        $receiver->full_name            = $command->DTO->fullName;
        $receiver->phone                = $command->DTO->phone;
        $receiver->additional_phone     = $command->DTO->additionalPhone;
        $receiver->latitude             = $command->DTO->latitude;
        $receiver->longitude            = $command->DTO->longitude;
        $receiver->street               = $command->DTO->street;
        $receiver->house                = $command->DTO->house;
        $receiver->office               = $command->DTO->office;
        $receiver->index                = $command->DTO->index;
        $receiver->comment              = $command->DTO->comment;
        $receiver->warehouse_id         = $command->DTO->warehouseId;
        $receiver->created_at           = $command->DTO->createdAt;
        $receiver->sector_id            = $sector?->id ?? $dispatcherSector?->default_sector_id;
        $receiver->dispatcher_sector_id = $dispatcherSector?->id;

        $this->createReceiverRepository->create($receiver);
    }

    private function hasReceiver(?int $id): bool
    {
        return (bool)$this->receiverQuery->getById($id);
    }
}
