<?php

use App\Module\Status\Models\StatusType;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('status_types', function (Blueprint $table) {
            DB::transaction(function () {
                $statusType              = StatusType::find(5);
                $statusType->title       = 'Отмена заказа';
                $statusType->description = 'Статус означет что заказ отменен';
                $statusType->save();
            });
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('status_types', function (Blueprint $table) {
            DB::transaction(function () {
                $statusType              = StatusType::find(5);
                $statusType->title       = 'Забор отменен';
                $statusType->description = 'Статус означет что забор отменен';
                $statusType->save();
            });
        });
    }
};
