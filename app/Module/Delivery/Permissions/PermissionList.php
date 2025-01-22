<?php

declare(strict_types=1);

namespace App\Module\Delivery\Permissions;

final class PermissionList
{
    public const DELIVERY_INDEX = 'dispatcher.delivery-index';

    public const PREDICTION_REPORT = 'dispatcher.prediction-report';

    public const ROUTE_SHEET_INDEX = 'dispatcher.route-sheet-index';

    public const ROUTE_SHEET_RESEND = 'dispatcher.route-sheet-resend';
}
