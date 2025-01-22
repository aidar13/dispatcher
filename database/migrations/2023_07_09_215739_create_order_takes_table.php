<?php

use App\Module\Status\Models\StatusType;
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
        Schema::create('order_takes', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('status_id')->nullable()->default(StatusType::ID_NOT_ASSIGNED);
            $table->unsignedBigInteger('invoice_id')->nullable();
            $table->unsignedBigInteger('customer_id')->nullable();
            $table->unsignedBigInteger('courier_id')->nullable();
            $table->unsignedBigInteger('city_id')->nullable();
            $table->unsignedBigInteger('company_id')->nullable();
            $table->unsignedBigInteger('wait_list_status_id')->nullable();
            $table->string('take_date');
            $table->integer('shipment_type')->nullable();
            $table->integer('places')->nullable();
            $table->float('weight')->nullable();
            $table->float('volume')->nullable();
            $table->unsignedBigInteger('internal_id')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::dropIfExists('order_takes');
    }
};
