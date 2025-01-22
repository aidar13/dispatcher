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
    public function up(): void
    {
        Schema::table('couriers', function (Blueprint $table) {
            $table->tinyInteger('routing_enabled')->default(0)->after('payment_amount');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::table('couriers', function (Blueprint $table) {
            $table->dropColumn('routing_enabled');
        });
    }
};
