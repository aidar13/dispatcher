<?php

declare(strict_types=1);

namespace App\Module\Order\Handlers;

use App\Module\DispatcherSector\Contracts\Queries\DispatcherSectorPolygonQuery;
use App\Module\DispatcherSector\Contracts\Queries\SectorPolygonQuery;
use App\Module\Order\Commands\SetSenderDispatcherSectorCommand;
use App\Module\Order\Contracts\Queries\SenderQuery;
use App\Module\Order\Contracts\Repositories\UpdateSenderRepository;
use App\Module\Order\Events\SenderUpdatedEvent;

final readonly class SetSenderDispatcherSectorHandler
{
    public function __construct(
        private SenderQuery $senderQuery,
        private UpdateSenderRepository $updateSenderRepository,
        private DispatcherSectorPolygonQuery $dispatcherSectorQuery,
        private SectorPolygonQuery $sectorQuery
    ) {
    }

    public function handle(SetSenderDispatcherSectorCommand $command): void
    {
        $sender = $this->senderQuery->getById($command->senderId);

        if (!$sender) {
            return;
        }

        $dispatcherSector = $this->dispatcherSectorQuery->findByCoordinates($sender->latitude, $sender->longitude);
        $sector           = $this->sectorQuery->findByCoordinates($sender->latitude, $sender->longitude);

        $sender->dispatcher_sector_id = $dispatcherSector?->id;
        $sender->sector_id            = $sector?->id ?? $dispatcherSector?->default_sector_id;

        $this->updateSenderRepository->update($sender);

        event(new SenderUpdatedEvent($sender->id));
    }
}
