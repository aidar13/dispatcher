<?php

use App\Module\Courier\Models\CourierStatus;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::table('courier_statuses')->insert([
            'id'    => CourierStatus::ID_IN_CHECKUP,
            'title' => 'На проверке(СБ)',
        ]);
        DB::table('courier_statuses')->insert([
            'id'    => CourierStatus::ID_ACTIVE,
            'title' => 'Активный',
        ]);
        DB::table('courier_statuses')->insert([
            'id'    => CourierStatus::ID_ARCHIVE,
            'title' => 'В архиве',
        ]);
        DB::table('courier_statuses')->insert([
            'id'    => CourierStatus::ID_REJECTED,
            'title' => 'Отказ СБ',
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::table('courier_statuses')->delete(CourierStatus::ID_IN_CHECKUP);
        DB::table('courier_statuses')->delete(CourierStatus::ID_ACTIVE);
        DB::table('courier_statuses')->delete(CourierStatus::ID_ARCHIVE);
        DB::table('courier_statuses')->delete(CourierStatus::ID_REJECTED);
    }
};
