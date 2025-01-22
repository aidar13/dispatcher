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
        Schema::table('order_takes', function (Blueprint $table) {
            $table->unsignedBigInteger('order_id')->nullable()->after('internal_id');
            $table->string('order_number')->nullable()->after('order_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('order_takes', function (Blueprint $table) {
            $table->dropColumn('order_id');
            $table->dropColumn('order_number');
        });
    }
};
