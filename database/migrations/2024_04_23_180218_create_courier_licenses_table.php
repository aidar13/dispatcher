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
        Schema::create('courier_licenses', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('courier_id')->index();
            $table->string('identify_card_number')->comment('Номер удостоверения личности')->nullable();
            $table->string('identify_card_issue_date')->comment('Дата выдачи удостоверения личности')->nullable();
            $table->string('driver_license_number')->comment('Номер водительского удостоверения')->nullable();
            $table->string('driver_license_issue_date')->comment('Дата выдачи водительского удостоверения')->nullable();
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
        Schema::dropIfExists('courier_licenses');
    }
};
