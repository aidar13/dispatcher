<?php

declare(strict_types=1);

namespace App\Module\Planning\Permissions;

final class PermissionList
{
    public const PLANNING_INDEX             = 'dispatcher.planning-index';
    public const PLANNING_COURIER_INDEX     = 'dispatcher.planning-courier-index';
    public const CONTAINER_INDEX            = 'dispatcher.container-index';
    public const CONTAINER_GENERATE         = 'dispatcher.container-generate';
    public const CONTAINER_CREATE           = 'dispatcher.container-create';
    public const CONTAINER_ATTACH_INVOICE   = 'dispatcher.container-attach-invoice';
    public const CONTAINER_DESTROY          = 'dispatcher.container-destroy';
    public const CONTAINER_ASSIGN_COURIER   = 'dispatcher.container-assign-courier';
    public const CONTAINER_SEND_TO_ASSEMBLY = 'dispatcher.container-send-assembly';
    public const CONTAINER_INVOICE_DESTROY  = 'dispatcher.container-invoice-destroy';
}
