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
        Schema::create('deliveries', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('status_id')->nullable()->default(StatusType::ID_DELIVERY_CREATED);
            $table->unsignedBigInteger('invoice_id')->nullable();
            $table->string('invoice_number')->nullable();
            $table->unsignedBigInteger('customer_id')->nullable();
            $table->unsignedBigInteger('courier_id')->nullable();
            $table->unsignedBigInteger('city_id')->nullable();
            $table->unsignedBigInteger('company_id')->nullable();
            $table->unsignedBigInteger('wait_list_status_id')->nullable();
            $table->integer('places')->nullable();
            $table->float('weight')->nullable();
            $table->float('volume')->nullable();
            $table->float('volume_weight')->nullable();
            $table->string('delivery_receiver_name')->nullable();
            $table->string('courier_comment')->nullable();
            $table->string('delivered_at')->nullable();
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
        Schema::dropIfExists('deliveries');
    }
};
