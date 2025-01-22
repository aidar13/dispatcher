<?php

declare(strict_types=1);

namespace App\Module\CourierApp\Permissions;

final class PermissionList
{
    public const COURIER_STATE_STORE   = 'dispatcher.courier-app.here-state.store';
    public const CAR_OCCUPANCY_STORE   = 'dispatcher.courier-app.car-occupancy.store';
    public const COURIER_PAYMENT_STORE = 'dispatcher.courier-app.courier-payment.store';
    public const COURIER_PAYMENT_SHOW  = 'dispatcher.courier-app.courier-payment.show';

    public const COURIER_LOCATION_STORE = 'dispatcher.courier-app.location.store';

    // order-take
    public const ORDER_TAKE_INDEX                = 'dispatcher.courier-app.order-take.index';
    public const MASS_APPROVE_TAKES              = 'dispatcher.courier-app.order-take.mass-approve-takes';
    public const SAVE_SHORTCOMING_REPORT_FILES   = 'dispatcher.courier-app.order-take.save-shortcoming-report-files';
    public const SHOW_SHORTCOMING_REPORT_FILES   = 'dispatcher.courier-app.order-take.show-shortcoming-report-files';
    public const COURIERS_CALL_STORE             = 'dispatcher.courier-app.courier-call.store';
    public const ORDER_TAKE_SET_WAIT_LIST_STATUS = 'dispatcher.courier-app.order-take.set-wait-list-status';
    public const ORDER_TAKE_SAVE_PACK_CODE       = 'dispatcher.courier-app.order-take.save-pack-code';

    // delivery
    public const DELIVERY_INDEX                = 'dispatcher.courier-app.delivery.index';
    public const DELIVERY_APPROVE              = 'dispatcher.courier-app.delivery.approve';
    public const DELIVERY_SET_WAIT_LIST_STATUS = 'dispatcher.courier-app.delivery.set-wait-list-status';

    // courier
    public const COURIER_PROFILE = 'dispatcher.courier-app.courier.profile';
}
