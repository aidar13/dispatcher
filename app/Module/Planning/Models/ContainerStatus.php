<?php

declare(strict_types=1);

namespace App\Module\Planning\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property string $title
 */
final class ContainerStatus extends Model
{
    protected $table = 'container_statuses';

    const ID_CREATED                = 1;
    const ID_COURIER_APPOINTED      = 2;
    const ID_SEND_TO_ASSEMBLY       = 3;
    const ID_ASSEMBLED              = 4;
    const ID_PARTIALLY_ASSEMBLED    = 5;
    const ID_ROUTE_LIST_CREATED     = 6;
    const ID_FAST_DELIVERY_SELECTED = 7;

    const TITLE_CREATED                = 'Создан';
    const TITLE_COURIER_APPOINTED      = 'Назначен курьер';
    const TITLE_SEND_TO_ASSEMBLY       = 'Отправлен на сборку';
    const TITLE_ASSEMBLED              = 'Собран';
    const TITLE_PARTIALLY_ASSEMBLED    = 'Частично собран';
    const TITLE_ROUTE_LIST_CREATED     = 'Создан маршрутный лист';
    const TITLE_FAST_DELIVERY_SELECTED = 'Выбрана быстрая доставка';
}
