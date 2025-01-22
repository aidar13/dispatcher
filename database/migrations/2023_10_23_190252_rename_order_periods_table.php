<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        foreach ($this->updateData() as $record) {
            DB::table('order_periods')
                ->where('id', $record['id'])
                ->update($record);
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        foreach ($this->rollbackData() as $record) {
            DB::table('order_periods')
                ->where('id', $record['id'])
                ->update($record);
        }
    }

    private function updateData(): array
    {
        return [
            [
                'id'   => 1,
                'from' => '8:00',
                'to'   => '12:00'
            ],
            [
                'id'   => 2,
                'from' => '12:00',
                'to'   => '18:00'
            ]
        ];
    }

    private function rollbackData(): array
    {
        return [
            [
                'id'   => 1,
                'from' => '10:00',
                'to'   => '12:00'
            ],
            [
                'id'   => 2,
                'from' => '14:00',
                'to'   => '16:00'
            ]
        ];
    }
};
