<?php

declare(strict_types=1);

namespace Tests\Feature\CourierApp;

use App\Libraries\Codes\ResponseCodes;
use App\Models\User;
use App\Module\Car\Contracts\Queries\CarOccupancyTypeQuery;
use App\Module\Car\Models\CarOccupancy;
use App\Module\Car\Models\CarOccupancyType;
use App\Module\Courier\Models\Courier;
use App\Module\CourierApp\Commands\Delivery\IntegrationOneC\ChangeDeliveryStatusInOneCCommand;
use App\Module\Delivery\Models\Delivery;
use App\Module\DispatcherSector\Models\DispatcherSector;
use App\Module\File\Events\Integration\IntegrationCreateSavedFileEvent;
use App\Module\File\Models\File;
use App\Module\Notification\Events\Integration\SendWebNotificationEvent;
use App\Module\Order\Models\Invoice;
use App\Module\Order\Models\InvoiceCargo;
use App\Module\Order\Models\Order;
use App\Module\Order\Models\Receiver;
use App\Module\Status\Commands\SendOrderStatusToCabinetCommand;
use App\Module\Status\Models\RefStatus;
use App\Module\Status\Models\StatusType;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Bus;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

final class DeliveryTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;

    public function setUp(): void
    {
        parent::setUp();
        $this->withoutExceptionHandling();
    }

    public function testApproveDelivery()
    {
        Storage::fake('s3');
        Bus::fake([
            SendOrderStatusToCabinetCommand::class,
            ChangeDeliveryStatusInOneCCommand::class,
        ]);

        Event::fake([
            IntegrationCreateSavedFileEvent::class,
        ]);

        /** @var Order $order */
        $order = Order::factory()->create();

        /** @var Receiver $receiver */
        $receiver = Receiver::factory()->create(['warehouse_id' => null]);

        /** @var Invoice $invoice */
        $invoice = Invoice::factory()->create([
            'order_id'    => $order->id,
            'receiver_id' => $receiver->id,
        ]);
        InvoiceCargo::factory()->create(['invoice_id' => $invoice->id]);

        /** @var Courier $courier */
        $courier = Courier::factory()->create();

        /** @var Delivery $delivery */
        $delivery = Delivery::factory()->create([
            'courier_id' => $courier->id,
            'invoice_id' => $invoice->id,
        ]);

        /** @var CarOccupancyType $carOccupancyType */
        $carOccupancyType = CarOccupancyType::factory()->create(['percent' => 100]);

        CarOccupancy::factory()->create([
            'car_id'                => $courier->car_id,
            'user_id'               => $courier->user_id,
            'car_occupancy_type_id' => $carOccupancyType->id,
        ]);

        $data = [
            'deliveryReceiverName' => $this->faker->word,
            'deliveredAt'          => Carbon::now()->format('Y-m-d H:i:s'),
            'attachments'          => [UploadedFile::fake()->image('photo1.jpg')]
        ];

        $this->actingAs($courier->user)
            ->postJson(route('courier-app.delivery.approve', ['id' => $delivery->id]), $data)
            ->assertStatus(ResponseCodes::SUCCESS)
            ->assertJson(['message' => 'Доставка подтверждена!']);

        $this->assertDatabaseHas('files', [
            'type'          => File::TYPE_DELIVERY_APPROVE,
            'client_id'     => $delivery->invoice_id,
            'original_name' => $data['attachments'][0]->getClientOriginalName(),
            'user_id'       => $courier->user_id,
        ]);

        $this->assertDatabaseHas('deliveries', [
            'id'                     => $delivery->id,
            'status_id'              => StatusType::ID_DELIVERED,
            'delivered_at'           => (new Carbon($data['deliveredAt']))->format('Y-m-d H:i:s'),
            'delivery_receiver_name' => $data['deliveryReceiverName'],
        ]);

        $this->assertDatabaseHas('courier_stops', [
            'courier_id'  => $courier->id,
            'client_id'   => $delivery->id,
            'client_type' => Delivery::class,
        ]);

        $occupancy = $carOccupancyType->percent - (int)round((($invoice->cargo->cubature * 100) / $courier->car->cubature));

        $this->assertDatabaseHas('car_occupancy_types', [
            'percent' => max($occupancy, 0),
        ]);

        $this->assertDatabaseCount('car_occupancies', 2);

        /** @var CarOccupancyTypeQuery $carOccupancyTypeQuery */
        $carOccupancyTypeQuery = $this->app->make(CarOccupancyTypeQuery::class);

        $carOccupancyType = $carOccupancyTypeQuery->getByPercent(max($occupancy, 0));
        $this->assertDatabaseHas('car_occupancies', [
            'car_occupancy_type_id' => $carOccupancyType->id,
            'type_id'               => CarOccupancy::COURIER_WORK_TYPE_ID_DELIVERY,
            'client_id'             => $delivery->invoice_id,
            'client_type'           => Invoice::class,
            'user_id'               => $courier->user_id,
            'car_id'                => $courier->car_id,
        ]);
    }

    public function testApproveDeliveryViaVerification()
    {
        Storage::fake('s3');
        Bus::fake([
            SendOrderStatusToCabinetCommand::class,
            ChangeDeliveryStatusInOneCCommand::class,
        ]);

        Event::fake([
            IntegrationCreateSavedFileEvent::class,
        ]);

        /** @var Order $order */
        $order = Order::factory()->create();

        /** @var Receiver $receiver */
        $receiver = Receiver::factory()->create(['warehouse_id' => null]);

        /** @var Invoice $invoice */
        $invoice = Invoice::factory()->create([
            'order_id'    => $order->id,
            'receiver_id' => $receiver->id,
            'verify'      => $this->faker->numberBetween(1, 2),
        ]);
        InvoiceCargo::factory()->create(['invoice_id' => $invoice->id]);

        /** @var Courier $courier */
        $courier = Courier::factory()->create();

        $verificationTypes = [
            StatusType::ID_DELIVERY_CREATED,
            StatusType::ID_IN_DELIVERING
        ];

        /** @var Delivery $delivery */
        $delivery = Delivery::factory()->create([
            'courier_id'   => $courier->id,
            'invoice_id'   => $invoice->id,
            'status_id'    => $verificationTypes[$this->faker->numberBetween(0, 1)],
            'delivered_at' => null
        ]);

        /** @var CarOccupancyType $carOccupancyType */
        $carOccupancyType = CarOccupancyType::factory()->create(['percent' => 100]);

        CarOccupancy::factory()->create([
            'car_id'                => $courier->car_id,
            'user_id'               => $courier->user_id,
            'car_occupancy_type_id' => $carOccupancyType->id,
        ]);

        $data = [
            'invoiceNumber' => $invoice->verify === 1 ? $invoice->invoice_number : $invoice->dop_invoice_number,
            'deliveredAt'   => Carbon::now()->format('Y-m-d H:i:s'),
            'verifyType'    => $invoice->verify
        ];

        $this->actingAs($courier->user)
            ->postJson(route('courier-app.delivery.approve-via-verification'), $data)
            ->assertStatus(ResponseCodes::SUCCESS)
            ->assertJson(['message' => 'Доставка подтверждена!']);

        $this->assertDatabaseHas('deliveries', [
            'id'           => $delivery->id,
            'status_id'    => StatusType::ID_DELIVERED,
            'delivered_at' => (new Carbon($data['deliveredAt']))->format('Y-m-d H:i:s'),
        ]);

        $this->assertDatabaseHas('courier_stops', [
            'courier_id'  => $courier->id,
            'client_id'   => $delivery->id,
            'client_type' => Delivery::class,
        ]);

        $occupancy = $carOccupancyType->percent - (int)round((($invoice->cargo->cubature * 100) / $courier->car->cubature));

        $this->assertDatabaseHas('car_occupancy_types', [
            'percent' => max($occupancy, 0),
        ]);

        $this->assertDatabaseCount('car_occupancies', 2);

        /** @var CarOccupancyTypeQuery $carOccupancyTypeQuery */
        $carOccupancyTypeQuery = $this->app->make(CarOccupancyTypeQuery::class);

        $carOccupancyType = $carOccupancyTypeQuery->getByPercent(max($occupancy, 0));
        $this->assertDatabaseHas('car_occupancies', [
            'car_occupancy_type_id' => $carOccupancyType->id,
            'type_id'               => CarOccupancy::COURIER_WORK_TYPE_ID_DELIVERY,
            'client_id'             => $delivery->invoice_id,
            'client_type'           => Invoice::class,
            'user_id'               => $courier->user_id,
            'car_id'                => $courier->car_id,
        ]);
    }

    public function testSetWaitListStatus()
    {
        Bus::fake([
            ChangeDeliveryStatusInOneCCommand::class,
            SendOrderStatusToCabinetCommand::class,
        ]);

        Event::fake([
            SendWebNotificationEvent::class,
        ]);

        /** @var Delivery $delivery */
        $delivery = Delivery::factory()->create();

        DispatcherSector::factory()->create(['city_id' => $delivery->city_id]);

        /** @var RefStatus $waitListStatus */
        $waitListStatus = RefStatus::factory()->create([
            'wait_list_type' => $this->faker->randomDigitNotNull,
        ]);

        $data = [
            'statusCode' => $waitListStatus->code,
            'comment'    => $this->faker->text
        ];

        $response = $this->actingAs(User::factory()->create())->put(route(
            'courier-app.delivery.set-wait-list-status',
            $delivery->invoice_id
        ), $data);

        $response->assertStatus(ResponseCodes::SUCCESS)
            ->assertJson([
                'message' => 'Статус листа ожидание успешно присвоен!'
            ]);

        $this->assertDatabaseHas('deliveries', [
            'id'                  => $delivery->id,
            'wait_list_status_id' => $waitListStatus->id,
        ]);
    }
}
