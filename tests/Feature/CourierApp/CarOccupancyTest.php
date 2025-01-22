<?php

declare(strict_types=1);

namespace Tests\Feature\CourierApp;

use App\Libraries\Codes\ResponseCodes;
use App\Module\Car\Models\CarOccupancy;
use App\Module\Courier\Models\Courier;
use App\Module\Order\Models\Order;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

final class CarOccupancyTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;

    public function setUp(): void
    {
        parent::setUp();
        $this->withoutExceptionHandling();
    }

    public function testOrderTakeCarOccupancy()
    {
        /** @var Courier $courier */
        $courier = Courier::factory()->create();

        /** @var CarOccupancy $carOccupancy */
        $carOccupancy = CarOccupancy::factory()->make();

        $data = [
            'clientId'           => $carOccupancy->client_id,
            'carOccupancyTypeId' => $carOccupancy->car_occupancy_type_id,
        ];

        $this->actingAs($courier->user)
            ->postJson(route('courier-app.order-take.car-occupancy'), $data)
            ->assertStatus(ResponseCodes::SUCCESS)
            ->assertJson([
                'message' => "Заполненость авто создана!"
            ]);


        $this->assertDatabaseHas('car_occupancies', [
            'car_occupancy_type_id' => $data['carOccupancyTypeId'],
            'type_id'               => CarOccupancy::COURIER_WORK_TYPE_ID_TAKE,
            'client_id'             => $data['clientId'],
            'client_type'           => Order::class,
            'user_id'               => $courier->user_id,
            'car_id'                => $courier->car_id,
        ]);
    }
}
