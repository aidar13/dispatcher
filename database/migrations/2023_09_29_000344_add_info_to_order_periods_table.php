<?php

use App\Module\Take\Models\OrderPeriod;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        DB::table('order_periods')->insert($this->data());
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        foreach ($this->data() as $record) {
            DB::table('order_periods')->where($record)->delete();
        }
    }

    private function data(): array
    {
        return [
            [
                'id'         => OrderPeriod::ID_BEFORE_LUNCH,
                'from'       => '10:00',
                'to'         => '12:00',
                'title'      => OrderPeriod::TITLE_BEFORE_LUNCH,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
            [
                'id'         => OrderPeriod::ID_AFTER_LUNCH,
                'from'       => '14:00',
                'to'         => '16:00',
                'title'      => OrderPeriod::TITLE_AFTER_LUNCH,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
        ];
    }
};
