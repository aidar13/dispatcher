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
        Schema::table('wait_list_statuses', function (Blueprint $table) {
            $table->string('source')->nullable()
                ->after('parent_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::table('wait_list_statuses', function (Blueprint $table) {
            $table->dropColumn('source');
        });
    }
};
