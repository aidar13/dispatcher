<?php

namespace App\Module\Notification\Models;

use App\Traits\HasCrossDatabaseConnection;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property string $number
 * @property int $created_by
 * @property string $created_by_email
 * @property int $type
 * @property string $comment
 * @property bool $is_confirmed
 * @property mixed|array $emails
 * @property Carbon $created_at
 * @property Carbon $updated_at
 */
final class WaitListMessage extends Model
{
    use HasCrossDatabaseConnection;

    protected $table = 'wait_list_messages';

    const TAKE_CARGO_TYPE = 1;
    const DELIVERY_TYPE   = 2;

    public function __construct(array $attributes = [])
    {
        $this->connection = !app()->runningUnitTests() ? 'notification' : 'sqlite';
        $this->table      = $this->setTableName($this->connection, $this->table);

        parent::__construct($attributes);
    }
}
