<?php

use App\Module\Order\Models\ShipmentType;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;

return new class extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        DB::table('shipment_types')->insert($this->data());
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        foreach ($this->data() as $record) {
            DB::table('shipment_types')->where($record)->delete();
        }
    }

    private function data(): array
    {
        return [
            [
                'id'         => ShipmentType::ID_CAR,
                'title'      => ShipmentType::TITLE_CAR,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
            [
                'id'         => ShipmentType::ID_PLANE,
                'title'      => ShipmentType::TITLE_PLANE,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
        ];
    }
};
