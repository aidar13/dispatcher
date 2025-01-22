<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInvoiceCargoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::create('invoice_cargo', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('invoice_id');
            $table->string('cargo_name')->nullable();
            $table->integer('places')->nullable();
            $table->unsignedDouble('weight')->nullable();
            $table->unsignedDouble('volume')->nullable();
            $table->unsignedDouble('volume_weight')->nullable();
            $table->unsignedDouble('width')->nullable();
            $table->unsignedDouble('height')->nullable();
            $table->unsignedDouble('depth')->nullable();
            $table->text('annotation')->nullable();
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
        Schema::dropIfExists('invoice_cargo');
    }
}
