<?php

declare(strict_types=1);

namespace Tests\Feature\Delivery;

use App\Module\CourierApp\Commands\Delivery\ApproveDeliveryFromProviderCommand;
use App\Module\Delivery\Contracts\Repositories\UpdateDeliveryRepository;
use App\Module\Delivery\Models\ReturnDelivery;
use App\Module\Order\Models\Invoice;
use App\Module\Status\Commands\Integration\CreateOrderStatusCommand;
use App\Module\Status\DTO\Integration\CreateOrderStatusDTO;
use App\Module\Status\Models\OrderStatus;
use App\Module\Status\Models\RefStatus;
use App\Module\Status\Models\StatusSource;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Arr;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Bus;
use Mockery\MockInterface;
use Tests\TestCase;

final class ReturnDeliveryTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;

    public function testCreateReturnDelivery()
    {
        $this->mock(UpdateDeliveryRepository::class, function (MockInterface $mock) {
            $mock->shouldReceive('update');
        });

        /** @var OrderStatus $status */
        $status = OrderStatus::factory()->make([
            'source_id' => StatusSource::ID_1C
        ]);

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
            'invoice_id' => $dto->invoiceId,
            'user_id'    => $dto->userId,
            'created_at' => $dto->createdAt,
        ]);
    }

    public function testDeleteReturnDelivery()
    {
        Bus::fake(ApproveDeliveryFromProviderCommand::class);

        /** @var Invoice $invoice */
        $invoice = Invoice::factory()->create();

        /** @var ReturnDelivery $return */
        $return = ReturnDelivery::factory()->create([
            'invoice_id' => $invoice->id
        ]);

        /** @var OrderStatus $status */
        $status = OrderStatus::factory()->make();

        $dto                = new CreateOrderStatusDTO();
        $dto->id            = $this->faker->numberBetween(0, 100);
        $dto->invoiceId     = $invoice->id;
        $dto->invoiceNumber = $invoice->invoice_number;
        $dto->orderId       = $invoice->order_id;
        $dto->title         = $status->title;
        $dto->code          = Arr::random([RefStatus::CODE_DELIVERY_IN_PROGRESS, RefStatus::CODE_DELIVERED]);
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

        $this->assertSoftDeleted('return_deliveries', [
            'id'         => $return->id,
            'invoice_id' => $invoice->id
        ]);
    }
}
