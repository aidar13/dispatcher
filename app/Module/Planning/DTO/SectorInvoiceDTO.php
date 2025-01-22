<?php

declare(strict_types=1);

namespace App\Module\Planning\DTO;

use App\Module\DispatcherSector\Models\Sector;

final class SectorInvoiceDTO
{
    public int $sectorId;
    public int $dispatcherSectorId;
    public int $waveId;
    public string $date;
    public ?string $latitude;
    public ?string $longitude;
    public ?int $statusId;

    public function __construct(Sector $sector, int $waveId, string $date, ?int $statusId)
    {
        $this->sectorId           = $sector->id;
        $this->latitude           = $sector->latitude;
        $this->longitude          = $sector->longitude;
        $this->dispatcherSectorId = $sector->dispatcher_sector_id;
        $this->date               = $date;
        $this->waveId             = $waveId;
        $this->statusId           = $statusId;
    }
}
