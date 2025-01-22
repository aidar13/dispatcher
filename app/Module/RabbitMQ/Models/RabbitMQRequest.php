<?php

namespace App\Module\RabbitMQ\Models;

use App\Module\RabbitMQ\Strategies\DeliveryCreatedStrategy;
use App\Module\RabbitMQ\Strategies\DeliveryUpdatedStrategy;
use App\Module\RabbitMQ\Strategies\InvoiceCreatedStrategy;
use App\Module\RabbitMQ\Strategies\InvoiceUpdatedStrategy;
use App\Module\RabbitMQ\Strategies\OrderCreatedStrategy;
use App\Module\RabbitMQ\Strategies\OrderUpdatedStrategy;
use App\Module\RabbitMQ\Strategies\ReceiverCreatedStrategy;
use App\Module\RabbitMQ\Strategies\ReceiverUpdatedStrategy;
use App\Module\RabbitMQ\Strategies\SenderCreatedStrategy;
use App\Module\RabbitMQ\Strategies\SenderUpdatedStrategy;
use App\Module\RabbitMQ\Strategies\TakeCreatedStrategy;
use App\Module\RabbitMQ\Strategies\TakeUpdatedStrategy;
use Carbon\Carbon;
use Database\Factories\RabbitMQRequestFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @property int $id
 * @property string $channel
 * @property string $data
 * @property string $error
 * @property Carbon $success_at
 * @property Carbon $failed_at
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property Carbon $deleted_at
 */
class RabbitMQRequest extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'rabbit_mq_requests';

    protected static function newFactory(): RabbitMQRequestFactory
    {
        return RabbitMQRequestFactory::new();
    }

    public const TYPE_INVOICE_CREATED = 'invoice.created';
    public const TYPE_INVOICE_UPDATED = 'invoice.updated';

    public const TYPE_ORDER_CREATED = 'order.created';
    public const TYPE_ORDER_UPDATED = 'order.updated';

    public const TYPE_SENDER_CREATED = 'sender.created';
    public const TYPE_SENDER_UPDATED = 'sender.updated';

    public const TYPE_RECEIVER_CREATED = 'receiver.created';
    public const TYPE_RECEIVER_UPDATED = 'receiver.updated';

    public const TYPE_ORDER_TAKE_CREATED = 'courier-app.take-info.created';
    public const TYPE_ORDER_TAKE_UPDATED = 'courier-app.take-info.updated';

    public const TYPE_DELIVERY_CREATED = 'courier-app.delivery-info.created';
    public const TYPE_DELIVERY_UPDATED = 'courier-app.delivery-info.updated';

    public const STRATEGIES = [
        InvoiceCreatedStrategy::class,
        InvoiceUpdatedStrategy::class,
        OrderCreatedStrategy::class,
        OrderUpdatedStrategy::class,
        SenderCreatedStrategy::class,
        SenderUpdatedStrategy::class,
        ReceiverCreatedStrategy::class,
        ReceiverUpdatedStrategy::class,
        TakeCreatedStrategy::class,
        TakeUpdatedStrategy::class,
        DeliveryCreatedStrategy::class,
        DeliveryUpdatedStrategy::class,
    ];

    public function getData()
    {
        return json_decode($this->data);
    }
}
