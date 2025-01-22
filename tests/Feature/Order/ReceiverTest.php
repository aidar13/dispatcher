<?php

declare(strict_types=1);

namespace Tests\Feature\Order;

use App\Module\DispatcherSector\Contracts\Queries\DispatcherSectorPolygonQuery;
use App\Module\DispatcherSector\Contracts\Queries\SectorPolygonQuery;
use App\Module\DispatcherSector\Models\Sector;
use App\Module\Order\Commands\CreateReceiverCommand;
use App\Module\Order\Commands\UpdateReceiverCommand;
use App\Module\Order\DTO\ReceiverDTO;
use App\Module\Order\Models\Receiver;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Carbon;
use Mockery\MockInterface;
use Tests\TestCase;

final class ReceiverTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;

    public function testCreateReceiver()
    {
        /** @var Sector $sector */
        $sector = Sector::factory()->create();

        $this->mock(DispatcherSectorPolygonQuery::class, mock: function (MockInterface $mock) use ($sector) {
            $mock->shouldReceive('findByCoordinates')->andReturn($sector->dispatcherSector);
        });

        $this->mock(SectorPolygonQuery::class, function (MockInterface $mock) use ($sector) {
            $mock->shouldReceive('findByCoordinates')->andReturn($sector);
        });

        /** @var Receiver $receiver */
        $receiver = Receiver::factory()->make();

        $dto                  = new ReceiverDTO();
        $dto->id              = $this->faker->numberBetween(0, 100);
        $dto->cityId          = $receiver->city_id;
        $dto->fullAddress     = $receiver->full_address;
        $dto->title           = $receiver->title;
        $dto->fullName        = $receiver->full_name;
        $dto->phone           = $receiver->phone;
        $dto->additionalPhone = $receiver->additional_phone;
        $dto->latitude        = (string)$receiver->latitude;
        $dto->longitude       = (string)$receiver->longitude;
        $dto->street          = $receiver->street;
        $dto->house           = $receiver->house;
        $dto->office          = $receiver->office;
        $dto->index           = $receiver->index;
        $dto->comment         = $receiver->comment;
        $dto->warehouseId     = $receiver->warehouse_id;
        $dto->createdAt       = Carbon::now();

        dispatch(new CreateReceiverCommand($dto));

        $this->assertDatabaseHas('receivers', [
            'id'                   => $dto->id,
            'city_id'              => $dto->cityId,
            'full_address'         => $dto->fullAddress,
            'title'                => $dto->title,
            'full_name'            => $dto->fullName,
            'phone'                => $dto->phone,
            'additional_phone'     => $dto->additionalPhone,
            'latitude'             => $dto->latitude,
            'longitude'            => $dto->longitude,
            'street'               => $dto->street,
            'house'                => $dto->house,
            'office'               => $dto->office,
            'index'                => $dto->index,
            'comment'              => $dto->comment,
            'warehouse_id'         => $dto->warehouseId,
            'sector_id'            => $sector->id,
            'dispatcher_sector_id' => $sector->dispatcher_sector_id
        ]);
    }

    public function testUpdateReceiver()
    {
        /** @var Sector $sector */
        $sector = Sector::factory()->create();

        $this->mock(DispatcherSectorPolygonQuery::class, mock: function (MockInterface $mock) use ($sector) {
            $mock->shouldReceive('findByCoordinates')->andReturn($sector->dispatcherSector);
        });

        $this->mock(SectorPolygonQuery::class, function (MockInterface $mock) use ($sector) {
            $mock->shouldReceive('findByCoordinates')->andReturn($sector);
        });

        /** @var Receiver $receiverModel */
        $receiverModel = Receiver::factory()->create([
            'sector_id'            => null,
            'dispatcher_sector_id' => null
        ]);

        /** @var Receiver $receiver */
        $receiver = Receiver::factory()->make();

        $dto                  = new ReceiverDTO();
        $dto->id              = $receiverModel->id;
        $dto->cityId          = $receiver->city_id;
        $dto->fullAddress     = $receiver->full_address;
        $dto->title           = $receiver->title;
        $dto->fullName        = $receiver->full_name;
        $dto->phone           = $receiver->phone;
        $dto->additionalPhone = $receiver->additional_phone;
        $dto->latitude        = (string)$receiver->latitude;
        $dto->longitude       = (string)$receiver->longitude;
        $dto->street          = $receiver->street;
        $dto->house           = $receiver->house;
        $dto->office          = $receiver->office;
        $dto->index           = $receiver->index;
        $dto->comment         = $receiver->comment;
        $dto->warehouseId     = $receiver->warehouse_id;
        $dto->createdAt       = Carbon::now();

        dispatch(new UpdateReceiverCommand($dto));

        $this->assertDatabaseHas('receivers', [
            'id'                   => $receiverModel->id,
            'city_id'              => $dto->cityId,
            'full_address'         => $dto->fullAddress,
            'title'                => $dto->title,
            'full_name'            => $dto->fullName,
            'phone'                => $dto->phone,
            'additional_phone'     => $dto->additionalPhone,
            'latitude'             => $dto->latitude,
            'longitude'            => $dto->longitude,
            'street'               => $dto->street,
            'house'                => $dto->house,
            'office'               => $dto->office,
            'index'                => $dto->index,
            'comment'              => $dto->comment,
            'warehouse_id'         => $dto->warehouseId,
            'sector_id'            => $sector->id,
            'dispatcher_sector_id' => $sector->dispatcher_sector_id
        ]);
    }
}
