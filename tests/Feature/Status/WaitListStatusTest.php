<?php

declare(strict_types=1);

namespace Tests\Feature\Status;

use App\Http\Enums\RequestSource;
use App\Module\Delivery\Models\Delivery;
use App\Module\Notification\Contracts\Repositories\SendPushNotificationRepository;
use App\Module\Order\Models\Invoice;
use App\Module\Status\Commands\Integration\CreateWaitListStatusCommand;
use App\Module\Status\DTO\Integration\StoreWaitListStatusDTO;
use App\Module\Status\Models\WaitListStatus;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Carbon;
use Mockery\MockInterface;
use Tests\TestCase;

final class WaitListStatusTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;

    public function testCreateWaitListStatus()
    {
        $status = WaitListStatus::factory()->make();

        $dto             = new StoreWaitListStatusDTO();
        $dto->id         = $this->faker->numberBetween(0, 100);
        $dto->parentId   = $status->parent_id;
        $dto->code       = $status->code;
        $dto->stateId    = WaitListStatus::ID_IN_WORK_AT_CC;
        $dto->userId     = $status->user_id;
        $dto->value      = $status->value;
        $dto->comment    = $status->comment;
        $dto->clientId   = $status->client_id;
        $dto->clientType = $status->client_type;
        $dto->createdAt  = Carbon::now();
        $dto->source     = RequestSource::ARM->value;

        dispatch(new CreateWaitListStatusCommand($dto));

        $this->assertDatabaseHas('wait_list_statuses', [
            'id'          => $dto->id,
            'parent_id'   => $dto->parentId,
            'code'        => $dto->code,
            'state_id'    => $dto->stateId,
            'user_id'     => $dto->userId,
            'value'       => $dto->value,
            'comment'     => $dto->comment,
            'client_id'   => $dto->clientId,
            'client_type' => $dto->clientType,
            'created_at'  => $dto->createdAt,
            'source'      => $dto->source,
        ]);
    }

    public function testCreateConfirmedWaitListStatus()
    {
        $this->mock(SendPushNotificationRepository::class, function (MockInterface $mock) {
            $mock->shouldReceive('send')->once();
        });

        /** @var Delivery $delivery */
        $delivery = Delivery::factory()->create();
        /** @var WaitListStatus $parentStatus */
        $parentStatus = WaitListStatus::factory()->create([
            'state_id'    => WaitListStatus::ID_IN_WORK_AT_CC,
            'client_type' => Invoice::class,
            'client_id'   => $delivery->invoice_id,
            'parent_id'   => null,
        ]);

        /** @var WaitListStatus $status */
        $status = WaitListStatus::factory()->make();

        $dto             = new StoreWaitListStatusDTO();
        $dto->id         = $this->faker->numberBetween(1, 100);
        $dto->parentId   = $parentStatus->id;
        $dto->code       = $status->code;
        $dto->stateId    = WaitListStatus::ID_CONFIRMED;
        $dto->userId     = $status->user_id;
        $dto->value      = $status->value;
        $dto->comment    = $status->comment;
        $dto->clientId   = $delivery->invoice_id;
        $dto->clientType = Invoice::class;
        $dto->createdAt  = Carbon::now();
        $dto->source     = RequestSource::ARM->value;

        dispatch(new CreateWaitListStatusCommand($dto));

        $this->assertDatabaseHas('wait_list_statuses', [
            'id'          => $dto->id,
            'parent_id'   => $dto->parentId,
            'code'        => $dto->code,
            'state_id'    => $dto->stateId,
            'user_id'     => $dto->userId,
            'value'       => $dto->value,
            'comment'     => $dto->comment,
            'client_id'   => $dto->clientId,
            'client_type' => $dto->clientType,
            'created_at'  => $dto->createdAt->toDateTimeString(),
            'source'      => $dto->source,
        ]);
    }
}
