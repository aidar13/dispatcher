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
        Schema::create('routings', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('type')->nullable();
            $table->unsignedBigInteger('courier_id')->nullable();
            $table->string('task_id')->nullable();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('dispatcher_sector_id')->nullable();
            $table->longText('response')->nullable();
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
        Schema::dropIfExists('routings');
    }
};
