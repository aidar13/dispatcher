<?php

declare(strict_types=1);

namespace App\Module\Order\Handlers;

use App\Module\DispatcherSector\Contracts\Queries\DispatcherSectorPolygonQuery;
use App\Module\DispatcherSector\Contracts\Queries\SectorPolygonQuery;
use App\Module\Order\Commands\CreateReceiverCommand;
use App\Module\Order\Commands\UpdateReceiverCommand;
use App\Module\Order\Contracts\Queries\ReceiverQuery;
use App\Module\Order\Contracts\Repositories\UpdateReceiverRepository;

final class UpdateReceiverHandler
{
    public function __construct(
        private readonly ReceiverQuery $receiverQuery,
        private readonly UpdateReceiverRepository $updateReceiverRepository,
        private readonly SectorPolygonQuery $sectorQuery,
        private readonly DispatcherSectorPolygonQuery $dispatcherSectorQuery
    ) {
    }

    public function handle(UpdateReceiverCommand $command): void
    {
        $receiver = $this->receiverQuery->getById($command->DTO->id);

        if (!$receiver) {
            dispatch(new CreateReceiverCommand($command->DTO));
            return;
        }

        $sector           = $this->sectorQuery->findByCoordinates($command->DTO->latitude, $command->DTO->longitude);
        $dispatcherSector = $this->dispatcherSectorQuery->findByCoordinates($command->DTO->latitude, $command->DTO->longitude);

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
        $receiver->sector_id            = $sector?->id ?? $dispatcherSector?->default_sector_id;
        $receiver->dispatcher_sector_id = $dispatcherSector?->id;

        $this->updateReceiverRepository->update($receiver);
    }
}
