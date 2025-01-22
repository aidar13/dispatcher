<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
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
        Schema::table('route_sheets', function (Blueprint $table) {
            $table->unsignedBigInteger('dispatcher_sector_id')->nullable()->after('status_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('route_sheets', function (Blueprint $table) {
            $table->dropColumn('dispatcher_sector_id');
        });
    }
};
