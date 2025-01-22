<?php

declare(strict_types=1);

namespace App\Module\Status\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @property int $id
 * @property string $text
 * @property int $type_id
 */
final class CommentTemplate extends Model
{
    use SoftDeletes;

    const ORDER_TAKE_TYPE_ID = 1;
    const DELIVERY_TYPE_ID = 2;
}
