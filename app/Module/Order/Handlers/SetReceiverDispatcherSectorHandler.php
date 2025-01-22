<?php

declare(strict_types=1);

namespace App\Module\Order\Handlers;

use App\Module\DispatcherSector\Contracts\Queries\DispatcherSectorPolygonQuery;
use App\Module\DispatcherSector\Contracts\Queries\SectorPolygonQuery;
use App\Module\Order\Commands\SetReceiverDispatcherSectorCommand;
use App\Module\Order\Contracts\Queries\ReceiverQuery;
use App\Module\Order\Contracts\Repositories\UpdateReceiverRepository;
use App\Module\Order\Events\ReceiverUpdatedEvent;
use Illuminate\Support\Facades\Log;

final readonly class SetReceiverDispatcherSectorHandler
{
    public function __construct(
        private ReceiverQuery $receiverQuery,
        private SectorPolygonQuery $sectorQuery,
        private UpdateReceiverRepository $receiverRepository,
        private DispatcherSectorPolygonQuery $dispatcherSectorQuery
    ) {
    }

    public function handle(SetReceiverDispatcherSectorCommand $command): void
    {
        $receiver = $this->receiverQuery->getById($command->receiverId);

        if (!$receiver) {
            return;
        }

        $dispatcherSector = $this->dispatcherSectorQuery->findByCoordinates($receiver->latitude, $receiver->longitude);
        $sector           = $this->sectorQuery->findByCoordinates($receiver->latitude, $receiver->longitude);

        $receiver->dispatcher_sector_id = $dispatcherSector?->id;
        $receiver->sector_id            = $sector?->id ?? $dispatcherSector?->default_sector_id;

        $this->receiverRepository->update($receiver);

        Log::info('Присвоение сектора получателя ID#' . $receiver->id, [
            'receiverId'         => $receiver->id,
            'dispatcherSectorId' => $receiver->dispatcher_sector_id,
            'sectorId'           => $receiver->sector_id,
        ]);

        event(new ReceiverUpdatedEvent($receiver->id));
    }
}
