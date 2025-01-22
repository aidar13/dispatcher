<?php

declare(strict_types=1);

namespace Feature\CourierApp;

use App\Libraries\Codes\ResponseCodes;
use App\Module\Courier\Models\Courier;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
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

    public function testProfileCourier()
    {
        /** @var Courier $courier */
        $courier = Courier::factory()->create();

        $this->actingAs($courier->user)
            ->get(route('courier-app.courier.profile'))
            ->assertStatus(ResponseCodes::SUCCESS)
            ->assertJsonStructure([
                'data' => [
                    'id',
                    'userId',
                    'fullName',
                    'iin',
                    'phone',
                    'cityName',
                    'latitude',
                    'longitude',
                    'car' => [
                        'id',
                        'number',
                        'model',
                        'companyId',
                        'createdAt',
                    ],
                ]
            ]);
    }

    public function testCheckCourier()
    {
        /** @var Courier $courier */
        $courier = Courier::factory()->create();

        $this->get(route('courier-app.courier.check-by-phone', $courier->phone_number))
            ->assertStatus(ResponseCodes::SUCCESS)
            ->assertJson([
                'message' => 'Success!'
            ]);
    }
}
