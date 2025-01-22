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
            $table->string('product_name')
                ->nullable()
                ->after('cargo_name');
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
            $table->dropColumn('product_name');
        });
    }
};
