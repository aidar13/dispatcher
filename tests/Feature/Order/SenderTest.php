<?php

declare(strict_types=1);

namespace Tests\Feature\Order;

use App\Module\DispatcherSector\Contracts\Queries\DispatcherSectorPolygonQuery;
use App\Module\DispatcherSector\Contracts\Queries\SectorPolygonQuery;
use App\Module\DispatcherSector\Models\Sector;
use App\Module\Order\Commands\CreateSenderCommand;
use App\Module\Order\Commands\UpdateSenderCommand;
use App\Module\Order\DTO\SenderDTO;
use App\Module\Order\Models\Sender;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Carbon;
use Mockery\MockInterface;
use Tests\TestCase;

final class SenderTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;

    public function testCreateSender()
    {
        /** @var Sector $sector */
        $sector = Sector::factory()->create();

        $this->mock(DispatcherSectorPolygonQuery::class, mock: function (MockInterface $mock) use ($sector) {
            $mock->shouldReceive('findByCoordinates')->andReturn($sector->dispatcherSector);
        });

        $this->mock(SectorPolygonQuery::class, function (MockInterface $mock) use ($sector) {
            $mock->shouldReceive('findByCoordinates')->andReturn($sector);
        });

        /** @var Sender $sender */
        $sender = Sender::factory()->make();

        $dto                  = new SenderDTO();
        $dto->id              = $this->faker->numberBetween(0, 100);
        $dto->title           = $sender->title;
        $dto->street          = $sender->street;
        $dto->house           = $sender->house;
        $dto->comment         = $sender->comment;
        $dto->latitude        = (string)$sender->latitude;
        $dto->longitude       = (string)$sender->longitude;
        $dto->fullAddress     = $sender->full_address;
        $dto->office          = $sender->office;
        $dto->index           = $sender->index;
        $dto->fullName        = $sender->full_name;
        $dto->phone           = $sender->phone;
        $dto->additionalPhone = $sender->phone;
        $dto->warehouseId     = $sender->warehouse_id;
        $dto->cityId          = $sender->city_id;
        $dto->createdAt       = Carbon::now();

        dispatch(new CreateSenderCommand($dto));

        $this->assertDatabaseHas('senders', [
            'id'                   => $dto->id,
            'title'                => $dto->title,
            'street'               => $dto->street,
            'house'                => $dto->house,
            'comment'              => $dto->comment,
            'latitude'             => $dto->latitude,
            'longitude'            => $dto->longitude,
            'full_address'         => $dto->fullAddress,
            'office'               => $dto->office,
            'index'                => $dto->index,
            'full_name'            => $dto->fullName,
            'phone'                => $dto->phone,
            'additional_phone'     => $dto->additionalPhone,
            'warehouse_id'         => $dto->warehouseId,
            'city_id'              => $dto->cityId,
            'sector_id'            => $sector->id,
            'dispatcher_sector_id' => $sector->dispatcher_sector_id
        ]);
    }

    public function testUpdateSender()
    {
        /** @var Sector $sector */
        $sector = Sector::factory()->create();

        $this->mock(DispatcherSectorPolygonQuery::class, mock: function (MockInterface $mock) use ($sector) {
            $mock->shouldReceive('findByCoordinates')->andReturn($sector->dispatcherSector);
        });

        $this->mock(SectorPolygonQuery::class, function (MockInterface $mock) use ($sector) {
            $mock->shouldReceive('findByCoordinates')->andReturn($sector);
        });

        /** @var Sender $senderModel */
        $senderModel = Sender::factory()->create([
            'sector_id'            => null,
            'dispatcher_sector_id' => null
        ]);

        /** @var Sender $sender */
        $sender = Sender::factory()->make();

        $dto                  = new SenderDTO();
        $dto->id              = $senderModel->id;
        $dto->title           = $sender->title;
        $dto->street          = $sender->street;
        $dto->house           = $sender->house;
        $dto->comment         = $sender->comment;
        $dto->latitude        = (string)$sender->latitude;
        $dto->longitude       = (string)$sender->longitude;
        $dto->fullAddress     = $sender->full_address;
        $dto->office          = $sender->office;
        $dto->index           = $sender->index;
        $dto->fullName        = $sender->full_name;
        $dto->phone           = $sender->phone;
        $dto->additionalPhone = $sender->additional_phone;
        $dto->warehouseId     = $sender->warehouse_id;
        $dto->cityId          = $sender->city_id;
        $dto->createdAt       = Carbon::now();

        dispatch(new UpdateSenderCommand($dto));

        $this->assertDatabaseHas('senders', [
            'id'                   => $senderModel->id,
            'title'                => $dto->title,
            'street'               => $dto->street,
            'house'                => $dto->house,
            'comment'              => $dto->comment,
            'latitude'             => $dto->latitude,
            'longitude'            => $dto->longitude,
            'full_address'         => $dto->fullAddress,
            'office'               => $dto->office,
            'index'                => $dto->index,
            'full_name'            => $dto->fullName,
            'phone'                => $dto->phone,
            'additional_phone'     => $dto->additionalPhone,
            'warehouse_id'         => $dto->warehouseId,
            'city_id'              => $dto->cityId,
            'sector_id'            => $sector->id,
            'dispatcher_sector_id' => $sector->dispatcher_sector_id
        ]);
    }
}
