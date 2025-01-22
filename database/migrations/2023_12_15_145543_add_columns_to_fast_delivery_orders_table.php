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
        Schema::table('fast_delivery_orders', function (Blueprint $table) {
            $table->tinyInteger('type')
                ->after('internal_id')
                ->nullable();
            $table->string('courier_name')
                ->after('type')
                ->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('fast_delivery_orders', function (Blueprint $table) {
            $table->dropColumn('type');
            $table->dropColumn('courier_name');
        });
    }
};
