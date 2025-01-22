<?php

use App\Module\Planning\Models\ContainerStatus;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::table('container_statuses')->insert($this->data());
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        foreach ($this->data() as $record) {
            DB::table('container_statuses')->where($record)->delete();
        }
    }

    private function data(): array
    {
        return [
            [
                'id'         => ContainerStatus::ID_CREATED,
                'title'      => ContainerStatus::TITLE_CREATED,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
            [
                'id'         => ContainerStatus::ID_COURIER_APPOINTED,
                'title'      => ContainerStatus::TITLE_COURIER_APPOINTED,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
            [
                'id'         => ContainerStatus::ID_SEND_TO_ASSEMBLY,
                'title'      => ContainerStatus::TITLE_SEND_TO_ASSEMBLY,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
            [
                'id'         => ContainerStatus::ID_ASSEMBLED,
                'title'      => ContainerStatus::TITLE_ASSEMBLED,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
            [
                'id'         => ContainerStatus::ID_PARTIALLY_ASSEMBLED,
                'title'      => ContainerStatus::TITLE_PARTIALLY_ASSEMBLED,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
            [
                'id'         => ContainerStatus::ID_ROUTE_LIST_CREATED,
                'title'      => ContainerStatus::TITLE_ROUTE_LIST_CREATED,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
            [
                'id'         => ContainerStatus::ID_FAST_DELIVERY_SELECTED,
                'title'      => ContainerStatus::TITLE_FAST_DELIVERY_SELECTED,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
        ];
    }
};
