<?php

declare(strict_types=1);

namespace App\Module\Inventory\Models;

final class Inventory
{
    public const WAREHOUSE_WRITE_OFF_TYPE_ID = 2;

    public const SPARK_BOX_L  = 'L';
    public const SPARK_BOX_M  = 'M';
    public const SPARK_BOX_S  = 'S';
    public const SPARK_BOX_XS = 'XS';
    public const ENVELOPE     = 'Конверт';
    public const PACKAGE      = 'Пакет';

    public const PACKAGE_INVENTORY_ITEM_ID      = 13;
    public const AIR_BUBBLE_INVENTORY_ITEM_ID   = 19;
    public const SPARK_BOX_L_INVENTORY_ITEM_ID  = 230;
    public const SPARK_BOX_M_INVENTORY_ITEM_ID  = 231;
    public const SPARK_BOX_S_INVENTORY_ITEM_ID  = 232;
    public const SPARK_BOX_XS_INVENTORY_ITEM_ID = 233;
    public const ENVELOPE_INVENTORY_ITEM_ID     = 245;

    public const AMOUNT_AIR_BUBBLE_FOR_SPARK_BOX_M  = 1.2;
    public const AMOUNT_AIR_BUBBLE_FOR_SPARK_BOX_S  = 0.6;
    public const AMOUNT_AIR_BUBBLE_FOR_SPARK_BOX_XS = 0.6;
}
