<?php

declare(strict_types=1);

namespace App\Module\DispatcherSector\Permissions;

final class PermissionList
{
    public const DISPATCHER_SECTOR_INDEX  = 'dispatcher.dispatcher-sectors-index';
    public const DISPATCHER_SECTOR_STORE  = 'dispatcher.dispatcher-sectors-store';
    public const DISPATCHER_SECTOR_UPDATE = 'dispatcher.dispatcher-sectors-update';
    public const DISPATCHER_SECTOR_DELETE = 'dispatcher.dispatcher-sectors-delete';

    public const SECTOR_INDEX  = 'dispatcher.sector-index';
    public const SECTOR_STORE  = 'dispatcher.sector-store';
    public const SECTOR_UPDATE = 'dispatcher.sector-update';
    public const SECTOR_DELETE = 'dispatcher.sector-delete';

    public const WAVE_INDEX    = 'dispatcher.wave-index';
    public const WAVE_INVOICES = 'dispatcher.wave-invoices';
    public const WAVE_STORE    = 'dispatcher.wave-store';
    public const WAVE_UPDATE   = 'dispatcher.wave-update';
    public const WAVE_DELETE   = 'dispatcher.wave-delete';
}
