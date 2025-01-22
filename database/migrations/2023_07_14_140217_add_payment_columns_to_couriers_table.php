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
            $table->unsignedInteger('schedule_type_id')->nullable()->after('iin');
            $table->unsignedInteger('payment_rate_type')->nullable()->after('schedule_type_id');
            $table->string('payment_amount')->nullable()->after('payment_rate_type');
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
            $table->dropColumn('payment_amount');
            $table->dropColumn('payment_rate_type');
            $table->dropColumn('schedule_type_id');
        });
    }
};
