<?php

declare(strict_types=1);

namespace Tests\Feature\Courier;

use App\Libraries\Codes\ResponseCodes;
use App\Models\User;
use App\Module\Car\Models\CarOccupancy;
use App\Module\Car\Models\CarOccupancyType;
use App\Module\Courier\Commands\Integration\CreateCourierCommand;
use App\Module\Courier\Commands\Integration\CreateCourierPaymentCommand;
use App\Module\Courier\Commands\Integration\UpdateCourierCommand;
use App\Module\Courier\Commands\UpdateCourierPhoneNumberInGatewayCommand;
use App\Module\Courier\DTO\CourierPaymentDTO;
use App\Module\Courier\DTO\Integration\CourierDTO;
use App\Module\Courier\DTO\Integration\CourierLicenseDTO;
use App\Module\Courier\Events\CourierUpdatedEvent;
use App\Module\Courier\Models\Courier;
use App\Module\Courier\Models\CourierLicense;
use App\Module\CourierApp\Models\CourierPayment;
use App\Module\File\Commands\Integration\IntegrationCreateFileCommand;
use App\Module\File\DTO\Integration\IntegrationFileDTO;
use App\Module\File\Models\File;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Bus;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

final class CourierTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;

    public function setUp(): void
    {
        parent::setUp();
        $this->faker = $this->faker('kk_KZ');
        $this->withoutExceptionHandling();
    }

    public function testCreateCourier()
    {
        /** @var Courier $courier */
        $courier = Courier::factory()->make(['id' => 1]);

        /** @var CourierLicense $courierLicense */
        $courierLicense = CourierLicense::factory()->make([
            'id'         => 1,
            'courier_id' => $courier->id
        ]);

        $courierLicenseDTO                         = new CourierLicenseDTO();
        $courierLicenseDTO->id                     = $courierLicense->id;
        $courierLicenseDTO->identifyCardNumber     = $courierLicense->identify_card_number;
        $courierLicenseDTO->identifyCardIssueDate  = $courierLicense->identify_card_issue_date;
        $courierLicenseDTO->driverLicenseNumber    = $courierLicense->driver_license_number;
        $courierLicenseDTO->driverLicenseIssueDate = $courierLicense->driver_license_issue_date;

        $dto                     = new CourierDTO();
        $dto->id                 = $courier->id;
        $dto->userId             = $courier->user_id;
        $dto->companyId          = $courier->company_id;
        $dto->statusId           = $courier->status_id;
        $dto->iin                = $courier->iin;
        $dto->dispatcherSectorId = $courier->dispatcher_sector_id;
        $dto->fullName           = $courier->full_name;
        $dto->phoneNumber        = $courier->phone_number;
        $dto->isActive           = $courier->is_active;
        $dto->code1C             = $courier->code_1c;
        $dto->createdAt          = $courier->created_at;
        $dto->carId              = $courier->car_id;
        $dto->paymentRateType    = $courier->payment_rate_type;
        $dto->paymentAmount      = $courier->payment_amount;
        $dto->courierLicense     = $courierLicenseDTO;

        dispatch(new CreateCourierCommand($dto));

        $this->assertDatabaseHas('couriers', [
            'id'                   => $dto->id,
            'user_id'              => $dto->userId,
            'company_id'           => $dto->companyId,
            'dispatcher_sector_id' => $dto->dispatcherSectorId,
            'full_name'            => $dto->fullName,
            'phone_number'         => $dto->phoneNumber,
            'is_active'            => $dto->isActive,
            'code_1c'              => $dto->code1C,
            'created_at'           => $dto->createdAt,
            'car_id'               => $dto->carId,
            'payment_rate_type'    => $dto->paymentRateType,
            'payment_amount'       => $dto->paymentAmount,
        ]);

        $this->assertDatabaseHas('courier_licenses', [
            'id'                        => $courierLicense->id,
            'courier_id'                => $courier->id,
            'identify_card_number'      => $courierLicense->identify_card_number,
            'identify_card_issue_date'  => $courierLicense->identify_card_issue_date,
            'driver_license_number'     => $courierLicense->driver_license_number,
            'driver_license_issue_date' => $courierLicense->driver_license_issue_date,
        ]);
    }

    public function testCreateCourierPayment()
    {
        /** @var CourierPayment $courierPayment */
        $courierPayment = CourierPayment::factory()->make();

        $dto             = new CourierPaymentDTO();
        $dto->id         = $courierPayment->id;
        $dto->clientId   = $courierPayment->client_id;
        $dto->clientType = $courierPayment->client_type;
        $dto->type       = $courierPayment->type;
        $dto->cost       = $courierPayment->cost;
        $dto->courierId  = $courierPayment->courier_id;

        dispatch(new CreateCourierPaymentCommand($dto));

        $this->assertDatabaseHas('courier_payments', [
            'id'          => $dto->id,
            'client_id'   => $dto->clientId,
            'client_type' => $dto->clientType,
            'type'        => $dto->type,
            'cost'        => $dto->cost * 100,
            'courier_id'  => $dto->courierId,
        ]);
    }

    public function testUpdateCourier()
    {
        /** @var Courier $courier */
        $courier = Courier::factory()->create();

        /** @var CourierLicense $courierLicense */
        $courierLicense = CourierLicense::factory()->create(['courier_id' => $courier->id]);

        $courierLicenseDTO                         = new CourierLicenseDTO();
        $courierLicenseDTO->id                     = $courier->id;
        $courierLicenseDTO->identifyCardNumber     = $courierLicense->identify_card_number;
        $courierLicenseDTO->identifyCardIssueDate  = $courierLicense->identify_card_issue_date;
        $courierLicenseDTO->driverLicenseNumber    = $courierLicense->driver_license_number;
        $courierLicenseDTO->driverLicenseIssueDate = $courierLicense->driver_license_issue_date;

        $dto                     = new CourierDTO();
        $dto->id                 = $courier->id;
        $dto->userId             = $courier->user_id;
        $dto->companyId          = $courier->company_id;
        $dto->statusId           = $courier->status_id;
        $dto->iin                = $courier->iin;
        $dto->dispatcherSectorId = $courier->dispatcher_sector_id;
        $dto->fullName           = $courier->full_name;
        $dto->phoneNumber        = $courier->phone_number;
        $dto->isActive           = $courier->is_active;
        $dto->code1C             = $courier->code_1c;
        $dto->createdAt          = $courier->created_at;
        $dto->carId              = $courier->car_id;
        $dto->paymentRateType    = $courier->payment_rate_type;
        $dto->paymentAmount      = $courier->payment_amount;
        $dto->courierLicense     = $courierLicenseDTO;

        dispatch(new UpdateCourierCommand($dto));

        $this->assertDatabaseHas('couriers', [
            'id'                   => $dto->id,
            'user_id'              => $dto->userId,
            'company_id'           => $dto->companyId,
            'dispatcher_sector_id' => $dto->dispatcherSectorId,
            'full_name'            => $dto->fullName,
            'phone_number'         => $dto->phoneNumber,
            'is_active'            => $dto->isActive,
            'code_1c'              => $dto->code1C,
            'created_at'           => $dto->createdAt,
            'car_id'               => $dto->carId,
            'payment_rate_type'    => $dto->paymentRateType,
            'payment_amount'       => $dto->paymentAmount,
        ]);

        $this->assertDatabaseHas('courier_licenses', [
            'courier_id'                => $courier->id,
            'identify_card_number'      => $courierLicense->identify_card_number,
            'identify_card_issue_date'  => $courierLicense->identify_card_issue_date,
            'driver_license_number'     => $courierLicense->driver_license_number,
            'driver_license_issue_date' => $courierLicense->driver_license_issue_date,
        ]);
    }

    public function testEditCourierData()
    {
        /** @var Courier $courierModel */
        $courierModel = Courier::factory()->create();

        /** @var Courier $courier */
        $courier = Courier::factory()->make();

        $data = [
            'iin'                => $courier->iin,
            'fullName'           => $courier->full_name,
            'dispatcherSectorId' => $courier->dispatcher_sector_id,
            'phoneNumber'        => $courier->phone_number,
            'paymentRateType'    => $courier->payment_rate_type,
            'paymentAmount'      => $courier->payment_amount,
            'companyId'          => $courier->company_id,
            'scheduleTypeId'     => $courier->schedule_type_id,
            'carId'              => $courier->car_id,
        ];

        $this->putJson(
            route('couriers.update', [$courierModel->id]),
            $data
        )->assertJson([
            'message' => 'Изменения успешно сохранены'
        ]);

        $this->assertDatabaseHas('couriers', [
            'id'               => $courierModel->id,
            'iin'              => $courier->iin,
            'schedule_type_id' => $courier->schedule_type_id,
            'car_id'           => $courier->car_id,
        ]);
    }

    public function testGetCouriers()
    {
        $couriers = Courier::factory()->count(5)->create();

        $this->get(route('couriers.index'))
            ->assertJsonStructure([
                'data' => [
                    '*' => [
                        'id',
                        'fullName',
                        'iin',
                        'phoneNumber',
                        'createdAt',
                        'code1C',
                        'routingEnabled',
                        'dispatcherSector' => [
                            'id',
                            'name',
                            'cityId'
                        ],
                        'status'           => [
                            'id',
                            'title',
                        ],
                        'car'              => [
                            'id',
                            'number',
                            'model',
                            'companyId',
                        ],
                        'company'          => [
                            'id',
                            'name',
                            'bin',
                        ],
                        'schedule'         => [
                            'id',
                            'workTimeFrom',
                            'workTimeUntil',
                            'shiftId',
                            'shift',
                        ],
                    ]
                ],
            ])
            ->assertJsonPath('data.*.id', $couriers->sortByDesc('id')->pluck('id')->toArray());
    }

    public function testShowCourier()
    {
        /** @var Courier $courier */
        $courier = Courier::factory()->create();

        $this->get(route('couriers.show', $courier->id))
            ->assertJsonStructure([
                'data' => [
                    'id',
                    'fullName',
                    'iin',
                    'phoneNumber',
                    'createdAt',
                    'dispatcherSector' => [
                        'id',
                        'name',
                        'cityId'
                    ],
                    'status'           => [
                        'id',
                        'title',
                    ],
                    'car'              => [
                        'id',
                        'number',
                        'model',
                        'companyId',
                        'carType' => ['id', 'title', 'capacity', 'volume'],
                        'company' => ['id', 'name', 'bin']
                    ],
                    'company'          => [
                        'id',
                        'name',
                        'bin',
                    ],
                    'schedule'         => [
                        'id',
                        'workTimeFrom',
                        'workTimeUntil',
                    ],
                    'driverLicenses',
                    'identificationCards',
                ],
            ])
            ->assertJsonPath('data.id', $courier->id);
    }

    public function testGetCourierTakeList()
    {
        $date = now();

        /** @var Collection $couriers */
        $couriers = Courier::factory()->count(5)->create(['user_id' => $this->faker->randomNumber()]);

        /** @var CarOccupancyType $carOccupancyType */
        $carOccupancyType = CarOccupancyType::factory()->create();

        /** @var Courier $courier */
        foreach ($couriers as $courier) {
            /** @var CarOccupancy $carOccupancyType */
            CarOccupancy::factory()->create([
                'car_occupancy_type_id' => $carOccupancyType->id,
                'user_id'               => $courier->user_id,
                'car_id'                => $courier->car_id,
                'type_id'               => CarOccupancy::COURIER_WORK_TYPE_ID_TAKE,
                'created_at'            => $date
            ]);
        }

        $this->get(route('couriers.take-list', ['date' => $date->format('Y-m-d')]))
            ->assertJsonStructure([
                'data' => [
                    '*' => [
                        'id',
                        'fullName',
                        'phoneNumber',
                        'dispatcherSector' => [
                            'id',
                            'name',
                            'cityId'
                        ],
                        'carOccupancy'     => [
                            'id',
                            'title',
                            'percent'
                        ]
                    ]
                ],
            ]);
    }

    public function testCouriersExport()
    {
        Courier::factory()->count(10)->create();

        $response = $this->get(route('couriers.export'));

        $response->assertStatus(ResponseCodes::SUCCESS)->assertDownload();
    }

    public function testCourierUploadDocument()
    {
        Storage::fake('s3');

        /** @var User $user */
        $user = User::factory()->create();

        /** @var Courier $courier */
        $courier = Courier::factory()->create();
        $file[]  = UploadedFile::fake()->create('file.png');

        $this->actingAs($user)
            ->postJson(route('courier.upload-document', $courier->id), [
                'type'  => File::TYPE_COURIER_DRIVER_LICENSE,
                'files' => $file
            ])->assertOk();

        $this->assertDatabaseHas('files', [
            'client_id'   => $courier->id,
            'client_type' => Courier::class,
            'type'        => File::TYPE_COURIER_DRIVER_LICENSE,
            'user_id'     => $user->id,
        ]);
    }

    public function testUpdatePhoneNumber()
    {
        Bus::fake([UpdateCourierPhoneNumberInGatewayCommand::class]);

        /** @var User $user */
        $user = User::factory()->create();

        /** @var Courier $courier */
        $courier = Courier::factory()->create();
        $file[]  = UploadedFile::fake()->create('file.png');

        $phone = $this->faker->numerify('+7707#######');

        $this->actingAs($user)
            ->putJson(route('courier.set-phone', $courier->id), [
                'phoneNumber' => $phone,
            ])->assertOk();

        $this->assertDatabaseHas('couriers', [
            'id'           => $courier->id,
            'phone_number' => str_replace('+', '', $phone),
        ]);

        Bus::assertDispatched(UpdateCourierPhoneNumberInGatewayCommand::class);
    }

    public function testUpdateRouting()
    {
        Event::fake(CourierUpdatedEvent::class);

        /** @var User $user */
        $user = User::factory()->create();

        /** @var Courier $courier */
        $courier = Courier::factory()->create();
        $routing = $this->faker->boolean;

        $this->actingAs($user)
            ->putJson(route('courier.update-routing', $courier->id), [
                'courierId' => $courier->id,
                'routingEnabled' => $routing
            ])->assertOk();

        $this->assertDatabaseHas('couriers', [
            'id'           => $courier->id,
            'routing_enabled' => $routing,
        ]);

        Event::assertDispatched(CourierUpdatedEvent::class);
    }

    public function testIntegrationCourierCreateFile()
    {
        $dto               = new IntegrationFileDTO();
        $dto->id           = $this->faker->randomNumber();
        $dto->path         = $this->faker->filePath();
        $dto->type         = $this->faker->randomNumber();
        $dto->originalName = $this->faker->word;
        $dto->clientId     = $this->faker->randomNumber();
        $dto->clientType   = $this->faker->word;
        $dto->userId       = $this->faker->randomNumber();
        $dto->uuidHash     = $this->faker->word;

        dispatch(new IntegrationCreateFileCommand($dto));

        $this->assertDatabaseHas('files', [
            'client_id'   => $dto->clientId,
            'client_type' => $dto->clientType,
            'type'        => $dto->type,
            'user_id'     => $dto->userId,
        ]);
    }
}
