<?php

declare(strict_types=1);

namespace Tests\Feature\CourierApp;

use App\Libraries\Codes\ResponseCodes;
use App\Module\Delivery\Models\Delivery;
use App\Module\Order\Models\Invoice;
use App\Module\Order\Models\InvoiceCargo;
use App\Module\Status\Models\StatusType;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Collection;
use Tests\TestCase;

final class CourierDeliveryTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;

    public function setUp(): void
    {
        parent::setUp();
        $this->withoutExceptionHandling();
    }

    public function testGetAllCourierDeliveries()
    {
        Delivery::factory()->count(5)->create([
            'status_id'  => StatusType::ID_IN_DELIVERING,
            'invoice_id' => InvoiceCargo::factory()->create()->invoice_id
        ]);

        $response = $this->get(route('courier-app.delivery.index'));

        $response->assertStatus(ResponseCodes::SUCCESS)
            ->assertJsonStructure([
                'data' => [
                    '*' => [
                        'id',
                        'companyName',
                        'createdAt',
                        'invoiceNumber',
                        'invoiceId',
                        'deliveredAt',
                        'weight',
                        'latitude',
                        'longitude',
                        'address',
                        'deliveryTime',
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
                        'status'       => [
                            'id',
                            'title'
                        ],
                        'nearTakeInfoIds',
                    ]
                ],
            ]);
    }

    public function testCourierDeliveriesShow()
    {
        /** @var Delivery $delivery */
        $delivery = Delivery::factory()->create([
            'status_id'  => StatusType::ID_IN_DELIVERING,
            'invoice_id' => Invoice::factory()->create()
        ]);

        InvoiceCargo::factory()->create([
           'invoice_id' => $delivery->invoice_id
        ]);

        $response = $this->get(route('courier-app.delivery.show', $delivery->id));

        $response->assertStatus(ResponseCodes::SUCCESS)
            ->assertJsonStructure([
                'data' => [
                    'id',
                    'invoiceNumber',
                    'weight',
                    'places',
                    'annotation',
                    'payerCompanyName',
                    'codPayment',
                    'cashSum',
                    'paymentMethod',
                    'sizeType',
                    'shouldReturnDocument',
                    'paymentTypeId',
                    'paymentTypeTitle',
                    'deliveryTime',
                    'nearTakeInfoIds',
                    'invoiceId',
                    'verify',
                    'canGenerateQr',
                    'verifyInvoiceNumber',
                    'nearTakeInfoIds',
                    'states',
                    'checks',
                    'receiver' => [
                        'id',
                        'fullName',
                        'address',
                        'office',
                        'house',
                        'comment',
                    ],
                    'company'  => [
                        'id',
                        'name',
                        'shortName',
                        'bin',
                    ],
                ]
            ]);
    }
}
