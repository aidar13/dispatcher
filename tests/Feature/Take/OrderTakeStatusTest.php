<?php

declare(strict_types=1);

namespace Tests\Feature\Take;

use App\Module\Notification\Contracts\Repositories\SendPushNotificationRepository;
use App\Module\Order\Models\Invoice;
use App\Module\Status\Commands\Integration\CreateOrderStatusCommand;
use App\Module\Status\DTO\Integration\CreateOrderStatusDTO;
use App\Module\Status\Models\OrderStatus;
use App\Module\Status\Models\RefStatus;
use App\Module\Status\Models\StatusType;
use App\Module\Take\Models\OrderTake;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Arr;
use Illuminate\Support\Carbon;
use Mockery\MockInterface;
use Tests\TestCase;

final class OrderTakeStatusTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;

    public function setUp(): void
    {
        parent::setUp();
        $this->withoutExceptionHandling();
    }

    public function testTakeSetAssignedStatus()
    {
        /** @var Invoice $invoice */
        $invoice = Invoice::factory()->create();

        /** @var OrderTake $take */
        $take = OrderTake::factory()->create([
            'invoice_id' => $invoice->id,
            'status_id'  => Arr::random([
                StatusType::ID_ASSIGNED,
                StatusType::ID_NOT_ASSIGNED,
            ])
        ]);

        /** @var OrderStatus $status */
        $status = OrderStatus::factory()->make();

        $dto                = new CreateOrderStatusDTO();
        $dto->id            = $this->faker->numberBetween(0, 100);
        $dto->invoiceId     = $invoice->id;
        $dto->invoiceNumber = $invoice->invoice_number;
        $dto->orderId       = $invoice->order_id;
        $dto->title         = $status->title;
        $dto->code          = RefStatus::CODE_ASSIGNED_TO_COURIER;
        $dto->comment       = $status->comment;
        $dto->sourceId      = $status->source_id;
        $dto->userId        = $status->user_id;
        $dto->createdAt     = Carbon::now();

        dispatch(new CreateOrderStatusCommand($dto));

        $this->assertDatabaseHas('order_takes', [
            'id'         => $take->id,
            'invoice_id' => $invoice->id,
            'status_id'  => StatusType::ID_ASSIGNED
        ]);
    }

    public function testTakeSetTakenStatus()
    {
        /** @var Invoice $invoice */
        $invoice = Invoice::factory()->create();

        /** @var OrderTake $take */
        $take = OrderTake::factory()->create([
            'invoice_id' => $invoice->id,
            'status_id'  => Arr::random([
                StatusType::ID_ASSIGNED,
                StatusType::ID_NOT_ASSIGNED,
            ])
        ]);

        /** @var OrderStatus $status */
        $status = OrderStatus::factory()->make();

        $dto                = new CreateOrderStatusDTO();
        $dto->id            = $this->faker->numberBetween(0, 100);
        $dto->invoiceId     = $invoice->id;
        $dto->invoiceNumber = $invoice->invoice_number;
        $dto->orderId       = $invoice->order_id;
        $dto->title         = $status->title;
        $dto->code          = RefStatus::CODE_CARGO_PICKED_UP;
        $dto->comment       = $status->comment;
        $dto->sourceId      = $status->source_id;
        $dto->userId        = $status->user_id;
        $dto->createdAt     = Carbon::now();

        dispatch(new CreateOrderStatusCommand($dto));

        $this->assertDatabaseHas('order_takes', [
            'id'         => $take->id,
            'invoice_id' => $invoice->id,
            'status_id'  => StatusType::ID_TAKEN
        ]);
    }

    public function testTakeSetCargoHandlingStatus()
    {
        /** @var Invoice $invoice */
        $invoice = Invoice::factory()->create();

        /** @var OrderTake $take */
        $take = OrderTake::factory()->create(['invoice_id' => $invoice->id]);

        /** @var OrderStatus $status */
        $status = OrderStatus::factory()->make();

        $dto                = new CreateOrderStatusDTO();
        $dto->id            = $this->faker->numberBetween(0, 100);
        $dto->invoiceId     = $invoice->id;
        $dto->invoiceNumber = $invoice->invoice_number;
        $dto->orderId       = $invoice->order_id;
        $dto->title         = $status->title;
        $dto->code          = RefStatus::CODE_CARGO_HANDLING;
        $dto->comment       = $status->comment;
        $dto->sourceId      = $status->source_id;
        $dto->userId        = $status->user_id;
        $dto->createdAt     = Carbon::now();

        dispatch(new CreateOrderStatusCommand($dto));

        $this->assertDatabaseHas('order_takes', [
            'id'         => $take->id,
            'invoice_id' => $invoice->id,
            'status_id'  => StatusType::ID_CARGO_HANDLING
        ]);
    }

    public function testTakeCancelStatus()
    {
        $this->mock(SendPushNotificationRepository::class, function (MockInterface $mock) {
            $mock->shouldReceive('send');
        });

        /** @var OrderTake $orderTake */
        $orderTake = OrderTake::factory()->create();

        /** @var OrderStatus $status */
        $status = OrderStatus::factory()->make(['invoice_id' => $orderTake->invoice_id]);

        $dto                = new CreateOrderStatusDTO();
        $dto->id            = $status->id;
        $dto->invoiceId     = $status->invoice_id;
        $dto->invoiceNumber = $status->invoice_number;
        $dto->orderId       = $status->order_id;
        $dto->title         = $status->title;
        $dto->code          = $this->faker->randomElement(RefStatus::TAKE_CANCEL_STATUSES);
        $dto->comment       = $status->comment;
        $dto->sourceId      = $status->source_id;
        $dto->userId        = $status->user_id;
        $dto->createdAt     = \Illuminate\Support\Carbon::now();

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

        $this->assertDatabaseHas('order_takes', [
            'id'        => $orderTake->id,
            'status_id' => StatusType::ID_TAKE_CANCELED
        ]);
    }
}
