<?php

declare(strict_types=1);

namespace Tests\Fake\Queries;

use App\Module\CourierApp\Contracts\Queries\CourierLocation\CourierLocationQuery;
use App\Module\CourierApp\Models\CourierLoaction;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\WithFaker;

final class FakeCourierLocationQuery implements CourierLocationQuery
{
    use WithFaker;

    public function __construct(private readonly CourierLoaction|null $loaction = null)
    {
        $this->setUpFaker();
    }

    public function getFirstNearbyLocationByCourierId(int $courierId, Carbon $time, ?string $latitude, ?string $longitude): ?CourierLoaction
    {
        return $this->loaction;
    }
}
