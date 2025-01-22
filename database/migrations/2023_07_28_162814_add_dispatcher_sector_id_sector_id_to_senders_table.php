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
        Schema::table('senders', function (Blueprint $table) {
            $table->unsignedBigInteger('dispatcher_sector_id')
                ->nullable()
                ->after('warehouse_id');
            $table->unsignedBigInteger('sector_id')
                ->nullable()
                ->after('dispatcher_sector_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::table('senders', function (Blueprint $table) {
            $table->dropColumn('sector_id');
            $table->dropColumn('dispatcher_sector_id');
        });
    }
};
