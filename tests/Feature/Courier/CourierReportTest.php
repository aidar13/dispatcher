<?php

declare(strict_types=1);

namespace Tests\Feature\Courier;

use App\Models\User;
use App\Module\Courier\Events\Integration\IntegrationCloseCourierDayCreatedEvent;
use App\Module\Courier\Models\Courier;
use App\Module\CourierApp\Models\CourierPayment;
use App\Module\Delivery\Models\Delivery;
use App\Module\Order\Models\Invoice;
use App\Module\Order\Models\InvoiceCargo;
use App\Module\Status\Models\OrderStatus;
use App\Module\Status\Models\RefStatus;
use App\Module\Take\Models\OrderTake;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Event;
use Tests\TestCase;

final class CourierReportTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;

    public function setUp(): void
    {
        parent::setUp();
        $this->withoutExceptionHandling();
    }

    public function testGetCourierEndOfDay()
    {
        Courier::factory()->count(5)->create();

        $this->get(route('courier.end-of-day.index'))
            ->assertJsonStructure([
                'data'  => [
                    '*' => [
                        'id',
                        'fullName',
                        'info' => [
                            '*' => [
                                'date',
                                'takesTotal',
                                'takesShipped',
                                'deliveriesTotal',
                                'deliveriesDelivered',
                                'timeOfWork',
                                'waves',
                                'cash',
                                'codPayment',
                                'hasReturnDelivery',
                            ]
                        ]
                    ]
                ],
            ]);
    }

    public function testCloseCourierDay()
    {
        $date = Carbon::today()->format('Y-m-d');
        /** @var Courier $courier */
        $courier = Courier::factory()->create();

        /** @var User $user */
        $user = User::factory()->create();

        /** @var OrderTake $take */
        $take = OrderTake::factory()->create([
            'courier_id' => $courier->id,
            'take_date'  => $date,
        ]);
        /** @var Delivery $delivery */
        $delivery = Delivery::factory()->create([
            'courier_id' => $courier->id,
            'created_at' => $date,
        ]);
        /** @var OrderTake $takeWithoutInvoiceNumber */
        $takeWithoutInvoiceNumber = OrderTake::class::factory()->create([
            'courier_id' => $courier->id,
            'take_date'  => $date,
            'invoice_id' => Invoice::factory()->create(['invoice_number' => null]),
        ]);
        OrderStatus::factory()->create([
            'invoice_id' => $takeWithoutInvoiceNumber->invoice_id,
            'code'       => RefStatus::CODE_CARGO_HANDLING
        ]);

        $response = $this->actingAs($user)
            ->postJson(route('courier.close-day', $courier->id), ['date' => $date])
            ->assertOk()
            ->assertJson([
                'message' => 'Закрытия дня курьера выполнен!',
            ]);
        $response->assertJsonPath('data.errors', [
            __('validation.courier_close_day.cargo_handling', ['invoiceNumbers' => $take->invoice->invoice_number]),
            __('validation.courier_close_day.not_delivered', ['invoiceNumbers' => $delivery->invoice->invoice_number]),
            __('validation.courier_close_day.invoice_number', ['orderNumbers' => $takeWithoutInvoiceNumber->invoice->order->number])
        ]);
    }

    public function testStoreCloseCourierDay()
    {
        Event::fake(IntegrationCloseCourierDayCreatedEvent::class);

        $date = now();

        /** @var Courier $courier */
        $courier = Courier::factory()->create();

        /** @var User $user */
        $user = User::factory()->create(['id' => $this->faker->numberBetween(10, 100)]);

        /** @var OrderTake $take */
        $take     = OrderTake::factory()->create(['courier_id' => $courier->id, 'take_date' => $date]);

        /** @var Delivery $take */
        $delivery = Delivery::factory()->create(['courier_id' => $courier->id, 'created_at' => $date]);

        OrderStatus::factory()->create([
            'invoice_id' => $take->invoice_id,
            'code'       => RefStatus::CODE_CARGO_HANDLING
        ]);
        OrderStatus::factory()->create([
            'invoice_id' => $delivery->invoice_id,
            'code'       => RefStatus::CODE_DELIVERED,
        ]);

        $response = $this->actingAs($user)
            ->postJson(route('courier.close-day', $courier->id), ['date' => $date->format('Y-m-d')])
            ->assertOk()
            ->assertJson([
                'message' => 'Закрытия дня курьера выполнен!',
            ]);

        $response->assertJsonPath('data.errors', []);

        $this->assertDatabaseHas('close_courier_day', [
            'courier_id'   => $courier->id,
            'user_id'      => $user->id,
            'date'         => $date->format('Y-m-d'),
        ]);

        Event::dispatched(IntegrationCloseCourierDayCreatedEvent::class);
    }

    public function testGetShowCourierEndOfDay()
    {
        RefStatus::factory()->create([
            'id'   => RefStatus::ID_CARGO_HANDLING,
            'code' => RefStatus::CODE_CARGO_HANDLING,
        ]);

        RefStatus::factory()->create([
            'id'   => RefStatus::ID_DELIVERY_IN_PROGRESS,
            'code' => RefStatus::CODE_DELIVERY_IN_PROGRESS,
        ]);

        RefStatus::factory()->create([
            'id'   => RefStatus::ID_CAR_BREAKDOWN_ON_DELIVERY,
            'code' => RefStatus::CODE_CAR_BREAKDOWN_ON_DELIVERY,
        ]);

        $takeInvoice = Invoice::factory()->create([
            'status_id' => RefStatus::ID_CARGO_HANDLING,
        ]);

        $deliveryInvoice = Invoice::factory()->create([
            'status_id'    => RefStatus::ID_DELIVERY_IN_PROGRESS,
            'wait_list_id' => RefStatus::ID_CAR_BREAKDOWN_ON_DELIVERY,
        ]);

        $date = Carbon::today()->format('Y-m-d');

        /** @var Courier $courier */
        $courier = Courier::factory()->create();

        /** @var OrderTake $take */
        $take = OrderTake::factory()->create([
            'courier_id' => $courier->id,
            'take_date'  => $date,
            'invoice_id' => $takeInvoice->id,
        ]);
        /** @var Delivery $delivery */
        $delivery = Delivery::factory()->create([
            'courier_id' => $courier->id,
            'created_at' => $date,
            'invoice_id' => $deliveryInvoice->id,
        ]);

        InvoiceCargo::factory()->create(['invoice_id' => $delivery->invoice_id]);

        OrderStatus::factory()->create([
            'invoice_id' => $take->invoice_id,
            'code'       => RefStatus::CODE_CARGO_HANDLING,
            'created_at' => $date
        ]);
        OrderStatus::factory()->create([
            'invoice_id' => $delivery->invoice_id,
            'code'       => RefStatus::CODE_DELIVERY_IN_PROGRESS,
            'created_at' => $date
        ]);

        CourierPayment::factory()->create([
            'client_id'   => $delivery->invoice_id,
            'client_type' => Invoice::class,
            'type'        => CourierPayment::TYPE_COST_FOR_ROAD,
            'courier_id'  => $courier->id,
            'created_at'  => $date,
        ]);

        CourierPayment::factory()->create([
            'client_id'   => $delivery->invoice_id,
            'client_type' => Invoice::class,
            'type'        => CourierPayment::TYPE_COST_FOR_PARKING,
            'courier_id'  => $courier->id,
            'created_at'  => $date,
        ]);

        $this->get(route('courier.end-of-day.show', ['courierId' => $courier->id, 'date' => $date]))
            ->assertJsonStructure([
                'data' => [
                    'id',
                    'fullName',
                    'info' => [
                        'date',
                        'takesTotal',
                        'takesShipped',
                        'deliveriesTotal',
                        'deliveriesDelivered',
                        'returnDeliveryCount',
                        'cancelledTakes',
                        'timeOfWork',
                        'cash',
                        'codPayment',
                        'hasReturnDelivery',
                        'isClosed',
                        'costForRoad',
                        'costForParking',
                        'detailsOfOrders' => [
                            '*' => [
                                'wave',
                                'routeSheet',
                                'isTake',
                                'isDelivery',
                                'orderNumber',
                                'weight',
                                'invoiceNumber',
                                'address',
                                'cash',
                                'codPayment',
                                'payerCompanyName',
                                'status',
                                'waitList',
                            ],
                        ],
                    ],
                ],
            ]);
    }
}
