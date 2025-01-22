<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('car_occupancies', function (Blueprint $table) {
            $table->renameColumn('occupancy_type', 'client_type');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('car_occupancies', function (Blueprint $table) {
            $table->renameColumn('client_type', 'occupancy_type');
        });
    }
};
