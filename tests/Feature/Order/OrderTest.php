<?php

declare(strict_types=1);

namespace Tests\Feature\Order;

use App\Module\Order\Commands\CreateOrderCommand;
use App\Module\Order\Commands\UpdateOrderCommand;
use App\Module\Order\DTO\OrderDTO;
use App\Module\Order\Models\Order;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Carbon;
use Tests\TestCase;

final class OrderTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;

    public function __construct(?string $name = null, array $data = [], $dataName = '')
    {
        parent::__construct($name, $data, $dataName);
        Carbon::setTestNow(now());
    }

    public function testCreateOrder()
    {
        /** @var Order $order */
        $order = Order::factory()->make();

        $dto            = new OrderDTO();
        $dto->id        = $this->faker->numberBetween(0, 100);
        $dto->companyId = $order->company_id;
        $dto->number    = $order->number;
        $dto->senderId  = $order->sender_id;
        $dto->userId    = $order->user_id;
        $dto->source    = $order->source;
        $dto->createdAt = Carbon::now();
        $dto->parentId  = null;

        dispatch(new CreateOrderCommand($dto));

        $this->assertDatabaseHas('orders', [
            'id'         => $dto->id,
            'company_id' => $dto->companyId,
            'number'     => $dto->number,
            'sender_id'  => $dto->senderId,
            'user_id'    => $dto->userId,
            'source'     => $dto->source,
            'parent_id'  => $dto->parentId,
        ]);
    }

    public function testUpdateOrder()
    {
        /** @var Order $orderModel */
        $orderModel = Order::factory()->create();
        /** @var Order $order */
        $order = Order::factory()->make();

        $dto            = new OrderDTO();
        $dto->id        = $orderModel->id;
        $dto->companyId = $order->company_id;
        $dto->number    = $order->number;
        $dto->senderId  = $order->sender_id;
        $dto->userId    = $order->user_id;
        $dto->source    = $order->source;
        $dto->parentId  = $order->parent_id;

        dispatch(new UpdateOrderCommand($dto));

        $this->assertDatabaseHas('orders', [
            'id'         => $orderModel->id,
            'company_id' => $order->company_id,
            'number'     => $order->number,
            'sender_id'  => $order->sender_id,
            'user_id'    => $order->user_id,
            'source'     => $order->source,
            'parent_id'  => $order->parent_id,
        ]);
    }
}
