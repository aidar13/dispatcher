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
        Schema::table('dispatcher_sectors', function (Blueprint $table) {
            $table->unsignedBigInteger('default_sector_id')->nullable()->after('delivery_manager_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::table('dispatcher_sectors', function (Blueprint $table) {
            $table->dropColumn('default_sector_id');
        });
    }
};
