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
        Schema::table('invoice_cargo', function (Blueprint $table) {
            $table->string('pack_code')
                ->after('cargo_name')
                ->nullable();
            $table->string('size_type')
                ->after('pack_code')
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
        Schema::table('invoice_cargo', function (Blueprint $table) {
            $table->dropColumn('pack_code');
            $table->dropColumn('size_type');
        });
    }
};
