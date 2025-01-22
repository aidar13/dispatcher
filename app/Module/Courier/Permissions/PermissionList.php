<?php

declare(strict_types=1);

namespace App\Module\Courier\Permissions;

final class PermissionList
{
    public const COURIER_INDEX  = 'dispatcher.courier-index';
    public const COURIER_EXPORT = 'dispatcher.courier-index';
    public const COURIER_UPDATE = 'dispatcher.courier-update';

    //end-of-day permissions
    public const COURIER_REPORT    = 'dispatcher.courier-report';
    public const COURIER_CLOSE_DAY = 'dispatcher.courier-close-day';

    //courier-schedule permissions
    public const COURIER_SCHEDULE_STORE = 'dispatcher.courier-schedule-store';
    public const COURIER_SCHEDULE_SHOW = 'dispatcher.courier-schedule-show';
}
