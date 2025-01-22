<?php

declare(strict_types=1);

namespace App\Module\Order\Handlers;

use App\Module\DispatcherSector\Contracts\Queries\DispatcherSectorPolygonQuery;
use App\Module\DispatcherSector\Contracts\Queries\SectorPolygonQuery;
use App\Module\Order\Commands\CreateSenderCommand;
use App\Module\Order\Contracts\Queries\SenderQuery;
use App\Module\Order\Contracts\Repositories\CreateSenderRepository;
use App\Module\Order\Events\SenderCreatedEvent;
use App\Module\Order\Models\Sender;

final class CreateSenderHandler
{
    public function __construct(
        private readonly SenderQuery $senderQuery,
        private readonly CreateSenderRepository $createSenderRepository,
        private readonly SectorPolygonQuery $sectorQuery,
        private readonly DispatcherSectorPolygonQuery $dispatcherSectorQuery
    ) {
    }

    public function handle(CreateSenderCommand $command): void
    {
        if ($this->hasSender($command->DTO->id)) {
            return;
        }

        $sector           = $this->sectorQuery->findByCoordinates($command->DTO->latitude, $command->DTO->longitude);
        $dispatcherSector = $this->dispatcherSectorQuery->findByCoordinates($command->DTO->latitude, $command->DTO->longitude);

        $sender                       = new Sender();
        $sender->id                   = $command->DTO->id;
        $sender->city_id              = $command->DTO->cityId;
        $sender->full_address         = $command->DTO->fullAddress;
        $sender->title                = $command->DTO->title;
        $sender->full_name            = $command->DTO->fullName;
        $sender->phone                = $command->DTO->phone;
        $sender->additional_phone     = $command->DTO->additionalPhone;
        $sender->latitude             = $command->DTO->latitude;
        $sender->longitude            = $command->DTO->longitude;
        $sender->street               = $command->DTO->street;
        $sender->house                = $command->DTO->house;
        $sender->office               = $command->DTO->office;
        $sender->index                = $command->DTO->index;
        $sender->comment              = $command->DTO->comment;
        $sender->warehouse_id         = $command->DTO->warehouseId;
        $sender->created_at           = $command->DTO->createdAt;
        $sender->sector_id            = $sector?->id ?? $dispatcherSector?->default_sector_id;
        $sender->dispatcher_sector_id = $dispatcherSector?->id;

        $this->createSenderRepository->create($sender);

        event(new SenderCreatedEvent($sender->id));
    }

    private function hasSender(?int $id): bool
    {
        return (bool)$this->senderQuery->getById($id);
    }
}
