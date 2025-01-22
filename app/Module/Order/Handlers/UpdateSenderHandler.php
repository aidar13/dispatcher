<?php

declare(strict_types=1);

namespace App\Module\Order\Handlers;

use App\Module\DispatcherSector\Contracts\Queries\DispatcherSectorPolygonQuery;
use App\Module\DispatcherSector\Contracts\Queries\SectorPolygonQuery;
use App\Module\Order\Commands\CreateSenderCommand;
use App\Module\Order\Commands\UpdateSenderCommand;
use App\Module\Order\Contracts\Queries\SenderQuery;
use App\Module\Order\Contracts\Repositories\UpdateSenderRepository;
use App\Module\Order\Events\SenderUpdatedEvent;

final class UpdateSenderHandler
{
    public function __construct(
        private readonly SenderQuery $senderQuery,
        private readonly UpdateSenderRepository $updateSenderRepository,
        private readonly SectorPolygonQuery $sectorQuery,
        private readonly DispatcherSectorPolygonQuery $dispatcherSectorQuery
    ) {
    }

    public function handle(UpdateSenderCommand $command): void
    {
        $sender = $this->senderQuery->getById($command->DTO->id);

        if (!$sender) {
            dispatch(new CreateSenderCommand($command->DTO));
            return;
        }

        $sector           = $this->sectorQuery->findByCoordinates($command->DTO->latitude, $command->DTO->longitude);
        $dispatcherSector = $this->dispatcherSectorQuery->findByCoordinates($command->DTO->latitude, $command->DTO->longitude);

        $sender->title                = $command->DTO->title;
        $sender->street               = $command->DTO->street;
        $sender->house                = $command->DTO->house;
        $sender->comment              = $command->DTO->comment;
        $sender->latitude             = $command->DTO->latitude;
        $sender->longitude            = $command->DTO->longitude;
        $sender->full_address         = $command->DTO->fullAddress;
        $sender->office               = $command->DTO->office;
        $sender->index                = $command->DTO->index;
        $sender->full_name            = $command->DTO->fullName;
        $sender->phone                = $command->DTO->phone;
        $sender->additional_phone     = $command->DTO->additionalPhone;
        $sender->warehouse_id         = $command->DTO->warehouseId;
        $sender->city_id              = $command->DTO->cityId;
        $sender->sector_id            = $sector?->id ?? $dispatcherSector?->default_sector_id;
        $sender->dispatcher_sector_id = $dispatcherSector?->id;

        $this->updateSenderRepository->update($sender);

        event(new SenderUpdatedEvent($sender->id));
    }
}
