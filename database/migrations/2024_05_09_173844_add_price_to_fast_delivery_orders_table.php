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
        Schema::table('fast_delivery_orders', function (Blueprint $table) {
            $table->string('internal_status')->nullable()->after('type');
            $table->string('price')->nullable()->after('internal_status');
            $table->string('courier_phone')->nullable()->after('courier_name');
            $table->string('tracking_url')->nullable()->after('courier_phone');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::table('fast_delivery_orders', function (Blueprint $table) {
            $table->dropColumn('tracking_url');
            $table->dropColumn('courier_phone');
            $table->dropColumn('price');
            $table->dropColumn('internal_status');
        });
    }
};
