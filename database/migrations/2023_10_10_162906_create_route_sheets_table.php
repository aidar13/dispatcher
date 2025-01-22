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
        Schema::create('route_sheets', function (Blueprint $table) {
            $table->id();
            $table->string('number')->unique();
            $table->integer('status_id')->nullable();
            $table->string('date')->nullable();
            $table->unsignedBigInteger('courier_id')->nullable();
            $table->unsignedBigInteger('city_id')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('route_sheets');
    }
};
