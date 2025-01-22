<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateReceiversTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('receivers', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('city_id')->nullable();
            $table->string('full_address')->nullable();
            $table->string('title')->nullable();
            $table->string('full_name', 255)->nullable();
            $table->string('phone', 255)->nullable();
            $table->string('additional_phone', 255)->nullable();
            $table->string('latitude')->nullable();
            $table->string('longitude')->nullable();
            $table->string('street')->nullable();
            $table->string('house')->nullable();
            $table->string('office', 255)->nullable();
            $table->string('index', 255)->nullable();
            $table->text('comment')->nullable();
            $table->unsignedBigInteger('warehouse_id')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('receivers');
    }
}
