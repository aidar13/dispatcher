<?php

declare(strict_types=1);

namespace Tests\Feature\CourierApp;

use App\Libraries\Codes\ResponseCodes;
use App\Models\User;
use App\Module\Courier\Models\Courier;
use App\Module\CourierApp\Events\OrderTake\InvoiceCargoSizeTypeSetEvent;
use App\Module\CourierApp\Events\OrderTake\OrderTakeStatusChangedEvent;
use App\Module\File\Models\File;
use App\Module\Gateway\Contracts\Integration\HttpClientRequest;
use App\Module\Notification\Contracts\Repositories\SendEmailNotificationRepository;
use App\Module\Order\Contracts\Repositories\Integration\CancelInvoiceRepository;
use App\Module\Order\Models\Invoice;
use App\Module\Order\Models\InvoiceCargo;
use App\Module\Order\Models\Order;
use App\Module\Status\Models\StatusType;
use App\Module\Take\Models\OrderTake;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Storage;
use Mockery\MockInterface;
use Tests\TestCase;

final class CourierOrderTakeTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;

    public function setUp(): void
    {
        parent::setUp();
        $this->withoutExceptionHandling();
    }

    public function testGetAllCourierTakes()
    {
        $date = Carbon::now()->format('Y-m-d');

        /** @var Order $order */
        $order = Order::factory()->create();

        /** @var Collection $orderTakes */
        OrderTake::factory()->count(5)->create([
            'take_date'  => $date,
            'invoice_id' => Invoice::factory()->create(['order_id' => $order->id]),
            'order_id'   => $order->id
        ]);

        $response = $this->get(route('courier-app.order-take.index'));

        $response->assertStatus(ResponseCodes::SUCCESS)
            ->assertJsonStructure([
                'data' => [
                    '*' => [
                        'orderId',
                        'orderNumber',
                        'latitude',
                        'longitude',
                        'position',
                        'customer'     => [
                            'id',
                            'fullName',
                            'address',
                            'phone',
                            'additionalPhone',
                            'sector' => [
                                'id',
                                'name',
                            ]
                        ],
                        'shipmentType' => [
                            'id',
                            'title'
                        ],
                        'takes'        => [
                            '*' => [
                                'takeId',
                                'places',
                                'weight',
                                'invoiceId',
                                'invoiceNumber'
                            ]
                        ],
                        'distance'
                    ]
                ],
            ]);
    }

    public function testGetCourierTakesByOrderId()
    {
        $date = Carbon::now()->format('Y-m-d');

        /** @var Order $order */
        $order = Order::factory()->create();

        /** @var Collection $orderTakes */
        OrderTake::factory()->count(5)->create([
            'take_date'  => $date,
            'invoice_id' => Invoice::factory()->create(),
            'order_id'   => $order->id
        ]);

        $response = $this->get(route('courier-app.order-take.show', $order->id));

        $response->assertStatus(ResponseCodes::SUCCESS)
            ->assertJsonStructure([
                'data'             => [
                    '*' => [
                        'takeId',
                        'places',
                        'weight',
                        'invoiceId',
                        'invoiceNumber',
                        'sizeType',
                        'hasSoftPackage',
                    ]
                ],
                'id',
                'orderId',
                'phone',
                'additionalPhone',
                'latitude',
                'longitude',
                'takeDate',
                'comment',
                'direction',
                'takePeriod',
                'orderNumber',
                'volume',
                'places',
                'volumeWeight',
                'company'          => [
                    'id',
                    'name',
                    'shortName',
                    'bin',
                ],
                'checks',
                'shortcomingFiles' => [
                    'reportFiles'  => [
                        '*' => [
                            'id',
                            'originalName',
                            'url',
                            'uuidHash',
                        ]
                    ],
                    'productFiles' => [
                        '*' => [
                            'id',
                            'originalName',
                            'url',
                            'uuidHash',
                        ]
                    ]
                ]
            ]);
    }

    public function testMassApproveTakes()
    {
        $this->mock(CancelInvoiceRepository::class, function (MockInterface $mock) {
            $mock->shouldReceive('cancel')->never();
        });

        Event::fake([
            OrderTakeStatusChangedEvent::class,
        ]);

        /** @var Order $order */
        $order = Order::factory()->create();

        $invoices = Invoice::factory(2)->create([
            'order_id' => $order->id,
        ])->each(fn(Invoice $invoice) => OrderTake::factory()->create([
            'invoice_id' => $invoice->id,
            'status_id'  => StatusType::ID_ASSIGNED
        ]));

        $notTakenInvoices = Invoice::factory(2)->create([
            'order_id' => $order->id,
        ])->each(fn(Invoice $invoice) => OrderTake::factory()->create([
            'invoice_id' => $invoice->id,
        ]));

        $dataInvoices = [];
        /** @var Invoice $invoice */
        foreach ($invoices as $invoice) {
            $dataInvoices[] = [
                'invoiceNumber' => $invoice->invoice_number,
                'places'        => $this->faker()->numberBetween(1, 10)
            ];
        }

        /** @var Courier $courier */
        $courier = Courier::factory()->create();

        $response = $this->actingAs($courier->user)
            ->postJson(route('courier-app.order-take.mass-approve'), [
                'orderId'  => $order->id,
                'invoices' => $dataInvoices
            ]);

        $response->assertStatus(ResponseCodes::SUCCESS)
            ->assertJson(['message' => 'Заборы успешно подтверждены!']);

        /** @var Invoice $invoice */
        foreach ($invoices as $key => $invoice) {
            $this->assertDatabaseHas('order_takes', [
                'id'        => $invoice->take->id,
                'places'    => $dataInvoices[$key]['places'],
                'status_id' => StatusType::ID_TAKEN,
            ]);
        }
    }

    public function testSaveShortcomingFiles()
    {
        Storage::fake('s3');

        $this->mock(SendEmailNotificationRepository::class, function (MockInterface $mock) {
            $mock->shouldReceive('send')->once();
        });
        $this->mock(HttpClientRequest::class, function (MockInterface $mock) {
            $mock->shouldReceive('makeRequest')->once();
        });

        /** @var User $user */
        $user = User::factory()->create();

        /** @var Invoice $invoice */
        $invoice = Invoice::factory()->create();

        $data = [
            'orderId'          => $invoice->order_id,
            'productFiles'     => [
                UploadedFile::fake()->image('photo1.jpg'),
                UploadedFile::fake()->image('photo2.jpg'),
            ],
            'shortcomingFiles' => [
                UploadedFile::fake()->image('photo3.jpg'),
                UploadedFile::fake()->image('photo4.jpg'),
            ],
        ];

        $this->actingAs($user)->post(
            route('courier-app.order-take.save-shortcoming-files'),
            $data,
        )
            ->assertStatus(ResponseCodes::SUCCESS)
            ->assertJson(['message' => 'Файлы успешно сохранены!']);

        foreach ($data['productFiles'] as $file) {
            $this->assertDatabaseHas('files', [
                'type'          => File::TYPE_COURIER_SHORTCOMING_PRODUCT,
                'client_id'     => $invoice->order_id,
                'client_type'   => Order::class,
                'original_name' => $file->getClientOriginalName(),
                'user_id'       => $user->id,
            ]);
        }

        foreach ($data['shortcomingFiles'] as $file) {
            $this->assertDatabaseHas('files', [
                'type'          => File::TYPE_COURIER_SHORTCOMING_REPORT,
                'client_id'     => $invoice->order_id,
                'client_type'   => Order::class,
                'original_name' => $file->getClientOriginalName(),
                'user_id'       => $user->id,
            ]);
        }
    }

    public function testShowShortcomingFiles()
    {
        Storage::fake('s3');

        $order = Order::factory()->create();

        File::factory()->create([
            'client_id'   => $order->id,
            'client_type' => Order::class,
            'type'        => File::TYPE_COURIER_SHORTCOMING_REPORT
        ]);

        File::factory()->create([
            'client_id'   => $order->id,
            'client_type' => Order::class,
            'type'        => File::TYPE_COURIER_SHORTCOMING_PRODUCT
        ]);

        $this->get(route('courier-app.order-take.show-shortcoming-files', $order->id))
            ->assertStatus(ResponseCodes::SUCCESS)
            ->assertJsonStructure([
                'data' => [
                    'reportFiles'  => [
                        '*' => [
                            'id',
                            'originalName',
                            'userId',
                            'userEmail',
                            'url',
                            'uuidHash',
                        ]
                    ],
                    'productFiles' => [
                        '*' => [
                            'id',
                            'originalName',
                            'userId',
                            'userEmail',
                            'url',
                            'uuidHash',
                        ]
                    ],
                ]
            ]);
    }

    public function testSaveInvoiceCargoPackCode()
    {
        Event::fake([
            InvoiceCargoSizeTypeSetEvent::class
        ]);

        /** @var InvoiceCargo $invoiceCargo */
        $invoiceCargo = InvoiceCargo::factory()->create();

        $packCode = $this->faker->word;

        $this->actingAs(new User())
            ->putJson(route('courier-app.order-take.save-pack-code', $invoiceCargo->invoice_id), [
                'packCode' => $packCode
            ])
            ->assertStatus(ResponseCodes::SUCCESS)
            ->assertJson([
                'message' => 'Штрихкод коробки успешно сохранен!'
            ]);

        $this->assertDatabaseHas('invoice_cargo', [
            'pack_code' => $packCode
        ]);

        Event::assertDispatched(InvoiceCargoSizeTypeSetEvent::class);
    }
}
