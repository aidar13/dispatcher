<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSlaSlaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::create('sla_sla', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('city_from');
            $table->unsignedBigInteger('city_to');
            $table->integer('pickup')->nullable();
            $table->integer('processing')->nullable();
            $table->integer('transit')->nullable();
            $table->integer('delivery')->nullable();
            $table->unsignedBigInteger('shipment_type_id');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::dropIfExists('sla_sla');
    }
}
