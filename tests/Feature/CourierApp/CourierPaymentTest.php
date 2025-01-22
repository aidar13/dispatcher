<?php

declare(strict_types=1);

namespace Tests\Feature\CourierApp;

use App\Libraries\Codes\ResponseCodes;
use App\Module\File\Models\File;
use App\Module\Order\Models\Invoice;
use App\Module\CourierApp\Models\CourierPayment;
use App\Module\Order\Models\Order;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

final class CourierPaymentTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;

    public function setUp(): void
    {
        parent::setUp();
        $this->withoutExceptionHandling();
    }
    public function testSaveCourierPaymentFilesForOrder()
    {
        Storage::fake('s3');

        /** @var CourierPayment $courierPayment */
        $courierPayment = CourierPayment::factory()->make();

        $data = [
            'clientId' => $courierPayment->client_id,
            'type'     => $courierPayment->type,
            'cost'     => $courierPayment->cost,
            'checks'   => [
                UploadedFile::fake()->image('photo1.jpg'),
                UploadedFile::fake()->image('photo2.jpg'),
            ],
        ];

        $response = $this->actingAs($courierPayment->courier->user)->post(route(
            'courier-app.order-take.courier-payment'
        ), $data);

        $response->assertStatus(ResponseCodes::SUCCESS)
            ->assertJson([
                'message' => 'Файлы успешно сохранены!'
            ]);

        $this->assertDatabaseHas('courier_payments', [
            'user_id'     => $courierPayment->courier->user->id,
            'client_id'   => $data['clientId'],
            'client_type' => Order::class,
            'type'        => $data['type'],
            'cost'        => $data['cost'] * 100,
        ]);

        foreach ($data['checks'] as $file) {
            $this->assertDatabaseHas('files', [
                'type'          => $courierPayment->getFileType(),
                'client_id'     => $data['clientId'],
                'client_type'   => Order::class,
                'original_name' => $file->getClientOriginalName(),
                'user_id'       => $courierPayment->courier->user_id,
            ]);
        }
    }

    public function testSaveCourierPaymentFilesForDelivery()
    {
        Storage::fake('s3');

        /** @var CourierPayment $courierPayment */
        $courierPayment = CourierPayment::factory()->make();

        $data = [
            'clientId' => $courierPayment->client_id,
            'type'     => $courierPayment->type,
            'cost'     => $courierPayment->cost,
            'checks'   => [
                UploadedFile::fake()->image('photo1.jpg'),
                UploadedFile::fake()->image('photo2.jpg'),
            ],
        ];

        $response = $this->actingAs($courierPayment->courier->user)->post(route(
            'courier-app.delivery.courier-payment'
        ), $data);

        $response->assertStatus(ResponseCodes::SUCCESS)
            ->assertJson([
                'message' => 'Файлы успешно сохранены!'
            ]);

        $this->assertDatabaseHas('courier_payments', [
            'user_id'     => $courierPayment->courier->user->id,
            'client_id'   => $data['clientId'],
            'client_type' => Invoice::class,
            'type'        => $data['type'],
            'cost'        => $data['cost'] * 100,
        ]);

        foreach ($data['checks'] as $file) {
            $this->assertDatabaseHas('files', [
                'type'          => $courierPayment->getFileType(),
                'client_id'     => $data['clientId'],
                'client_type'   => Invoice::class,
                'original_name' => $file->getClientOriginalName(),
                'user_id'       => $courierPayment->courier->user_id,
            ]);
        }
    }

    public function testShowCourierPaymentFilesByInvoiceId()
    {
        Storage::fake('s3');

        $invoice = Invoice::factory()->create();

        CourierPayment::factory()->create([
            'client_id'   => $invoice->id,
            'client_type' => Invoice::class,
        ]);

        File::factory()->create([
            'client_id'   => $invoice->id,
            'client_type' => Invoice::class,
        ]);

        $this->get(route('courier-app.delivery.courier-payment.show-by-invoice-id', $invoice->id))
            ->assertStatus(ResponseCodes::SUCCESS)
            ->assertJsonStructure([
                'data' => [
                    '*' => [
                        'id',
                        'courierId',
                        'userId',
                        'userEmail',
                        'clientId',
                        'clientType',
                        'type',
                        'typeName',
                        'cost',
                        'files',
                    ]
                ]
            ]);
    }

    public function testShowCourierPaymentFilesByOrderId()
    {
        Storage::fake('s3');

        $order = Order::factory()->create();

        CourierPayment::factory()->create([
            'client_id'   => $order->id,
            'client_type' => Order::class,
        ]);

        File::factory()->create([
            'client_id'   => $order->id,
            'client_type' => Order::class,
        ]);

        $this->get(route('courier-app.delivery.courier-payment.show-by-order-id', $order->id))
            ->assertStatus(ResponseCodes::SUCCESS)
            ->assertJsonStructure([
                'data' => [
                    '*' => [
                        'id',
                        'courierId',
                        'userId',
                        'userEmail',
                        'clientId',
                        'clientType',
                        'type',
                        'typeName',
                        'cost',
                        'files',
                    ]
                ]
            ]);
    }
}
