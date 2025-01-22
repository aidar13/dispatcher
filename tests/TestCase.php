<?php

namespace Tests;

use App\Models\User;
use App\Module\CourierApp\Contracts\Repositories\OrderTake\SetInvoiceCargoPackCodeRepository;
use App\Module\Gateway\Contracts\AuthRepository;
use App\Module\Gateway\Contracts\GatewayUserQuery;
use App\Module\History\Events\Integration\SendHistoryEvent;
use App\Module\Order\Commands\SetReceiverDispatcherSectorCommand;
use App\Module\Order\Commands\SetSenderDispatcherSectorCommand;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Support\Facades\Bus;
use Illuminate\Support\Facades\Event;
use Tests\Repositories\SetInvoiceCargoPackCodeFakeRepository;
use Tests\Repositories\AuthFakerRepository;
use Tests\Repositories\GatewayUserFakeRepository;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    public function setUp(): void
    {
        parent::setUp();
        $this->actingAs(User::factory()->create());

        $this->app->bind(AuthRepository::class, AuthFakerRepository::class);
        $this->app->bind(GatewayUserQuery::class, GatewayUserFakeRepository::class);
        $this->app->bind(SetInvoiceCargoPackCodeRepository::class, SetInvoiceCargoPackCodeFakeRepository::class);

        Bus::fake(SetReceiverDispatcherSectorCommand::class);
        Bus::fake(SetSenderDispatcherSectorCommand::class);

        Event::fake([SendHistoryEvent::class]);
    }
}
