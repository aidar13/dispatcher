<?php

declare(strict_types=1);

namespace App\Module\Order\Permissions;

final class PermissionList
{
    public const INVOICE_CHANGE_DELIVERY_DATE = 'dispatcher.invoice-update-delivery-date';
    public const INVOICE_UPDATE_WAVE          = 'dispatcher.invoice-update-wave';
    public const SET_FAST_DELIVERY_COURIER    = 'dispatcher.set-fast-delivery-courier';
    public const RESEND_STATUS_TO_ONE_C       = 'dispatcher.resend-delivery-status-onec';
}
