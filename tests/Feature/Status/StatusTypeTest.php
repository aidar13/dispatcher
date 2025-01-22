<?php

declare(strict_types=1);

namespace Tests\Feature\Status;

use App\Module\Status\Models\StatusType;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

final class StatusTypeTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;

    public function testGetStatusType()
    {
        $statuses = StatusType::all();

        $this->get(route('status-type.index'))
            ->assertJsonStructure([
                'data'  => [
                    '*' => [
                        'id',
                        'title',
                    ]
                ],
            ])
            ->assertJsonPath('data.*.id', $statuses->pluck('id')->toArray());
    }
}
