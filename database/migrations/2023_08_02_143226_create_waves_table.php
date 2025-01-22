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
        Schema::create('waves', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('dispatcher_sector_id')->nullable();
            $table->string('title');
            $table->time('from_time');
            $table->time('to_time');
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
        Schema::dropIfExists('waves');
    }
};
