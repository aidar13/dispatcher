<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
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
        Schema::dropIfExists('courier_schedules');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::create('courier_schedules', function (Blueprint $table) {
            $table->id();
            $table->foreignId('courier_id')
                ->constrained()
                ->cascadeOnDelete()
                ->cascadeOnUpdate();
            $table->unsignedTinyInteger('weekday');
            $table->time('work_time_from')->default('09:00:00');
            $table->time('work_time_until')->default('18:00:00');
            $table->softDeletes();
            $table->timestamps();
        });
    }
};
