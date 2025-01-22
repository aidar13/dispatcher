<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCouriersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('couriers', function (Blueprint $table) {
            $table->id();
	        $table->integer('status_id')->nullable();
	        $table->unsignedBigInteger('user_id')->nullable();
            $table->unsignedBigInteger('company_id')->nullable();
            $table->unsignedBigInteger('dispatcher_sector_id')->nullable();
            $table->unsignedBigInteger('car_id')->nullable();
            $table->boolean('is_active')->default(false);
            $table->string('full_name')->nullable();
            $table->string('phone_number')->nullable();
            $table->string('code_1c')->nullable();
            $table->string('iin')->nullable();
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
        Schema::dropIfExists('couriers');
    }
}
