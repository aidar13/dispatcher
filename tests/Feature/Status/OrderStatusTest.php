<?php

declare(strict_types=1);

namespace Tests\Feature\Status;

use App\Module\Status\Commands\Integration\CreateOrderStatusCommand;
use App\Module\Status\DTO\Integration\CreateOrderStatusDTO;
use App\Module\Status\Models\OrderStatus;
use App\Module\Status\Models\RefStatus;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Carbon;
use Tests\TestCase;

final class OrderStatusTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;

    public function testCreateOrderStatus()
    {
        /** @var OrderStatus $status */
        $status = OrderStatus::factory()->make();

        $dto                = new CreateOrderStatusDTO();
        $dto->id            = $this->faker->numberBetween(0, 100);
        $dto->invoiceId     = $status->invoice_id;
        $dto->invoiceNumber = $status->invoice_number;
        $dto->orderId       = $status->order_id;
        $dto->title         = $status->title;
        $dto->code          = $status->code;
        $dto->comment       = $status->comment;
        $dto->sourceId      = $status->source_id;
        $dto->userId        = $status->user_id;
        $dto->createdAt     = Carbon::now();

        dispatch(new CreateOrderStatusCommand($dto));

        $this->assertDatabaseHas('order_statuses', [
            'id'             => $dto->id,
            'invoice_id'     => $dto->invoiceId,
            'invoice_number' => $dto->invoiceNumber,
            'order_id'       => $dto->orderId,
            'code'           => $dto->code,
            'title'          => $dto->title,
            'comment'        => $dto->comment,
            'source_id'      => $dto->sourceId,
            'user_id'        => $dto->userId,
            'created_at'     => $dto->createdAt,
        ]);
    }

    public function testCreateReturnDelivery()
    {
        /** @var OrderStatus $status */
        $status = OrderStatus::factory()->make();

        $dto                = new CreateOrderStatusDTO();
        $dto->id            = $this->faker->numberBetween(0, 100);
        $dto->invoiceId     = $status->invoice_id;
        $dto->invoiceNumber = $status->invoice_number;
        $dto->orderId       = $status->order_id;
        $dto->title         = $status->title;
        $dto->code          = RefStatus::CODE_COURIER_RETURN_DELIVERY;
        $dto->comment       = $status->comment;
        $dto->sourceId      = $status->source_id;
        $dto->userId        = $status->user_id;
        $dto->createdAt     = Carbon::now();

        dispatch(new CreateOrderStatusCommand($dto));

        $this->assertDatabaseHas('order_statuses', [
            'id'             => $dto->id,
            'invoice_id'     => $dto->invoiceId,
            'invoice_number' => $dto->invoiceNumber,
            'order_id'       => $dto->orderId,
            'code'           => $dto->code,
            'title'          => $dto->title,
            'comment'        => $dto->comment,
            'source_id'      => $dto->sourceId,
            'user_id'        => $dto->userId,
            'created_at'     => $dto->createdAt,
        ]);

        $this->assertDatabaseHas('return_deliveries', [
            'invoice_id'     => $dto->invoiceId,
            'user_id'        => $dto->userId,
            'created_at'     => $dto->createdAt,
        ]);
    }
}
