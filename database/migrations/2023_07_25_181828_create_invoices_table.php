<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInvoicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('invoices', function (Blueprint $table) {
            $table->id();
            $table->string('invoice_number')->nullable();
            $table->unsignedBigInteger('order_id');
            $table->string('status_id')->nullable();
            $table->unsignedBigInteger('receiver_id');
            $table->unsignedBigInteger('direction_id');
            $table->integer('shipment_id');
            $table->integer('period_id')->nullable();
            $table->string('take_date')->nullable();
            $table->string('take_time')->nullable();
            $table->string('code_1c')->nullable();
            $table->string('dop_invoice_number')->nullable();
            $table->float('cash_sum')->nullable();
            $table->integer('should_return_document')->nullable();
            $table->integer('weekend_delivery')->nullable();
            $table->integer('verify')->nullable();
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
        Schema::dropIfExists('invoices');
    }
}
