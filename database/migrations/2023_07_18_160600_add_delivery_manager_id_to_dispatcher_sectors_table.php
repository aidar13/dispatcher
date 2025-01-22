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
        Schema::table('dispatcher_sectors', function (Blueprint $table) {
            $table->unsignedBigInteger('delivery_manager_id')->nullable()->after('courier_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('dispatcher_sectors', function (Blueprint $table) {
            $table->dropColumn('delivery_manager_id');
        });
    }
};
