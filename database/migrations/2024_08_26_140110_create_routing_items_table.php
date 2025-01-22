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
    public function up(): void
    {
        Schema::create('routing_items', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('routing_id');
            $table->tinyInteger('type');
            $table->morphs('client');
            $table->unsignedInteger('position');
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
        Schema::dropIfExists('routing_items');
    }
};
