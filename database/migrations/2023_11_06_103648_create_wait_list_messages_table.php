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
        if (app()->runningUnitTests()) {
            Schema::create('wait_list_messages', function (Blueprint $table) {
                $table->id();
                $table->string('number');
                $table->tinyInteger('type');
                $table->string('emails');
                $table->text('comment');
                $table->unsignedBigInteger('created_by');
                $table->string('created_by_email');
                $table->boolean('is_confirmed')->default(false);
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        if (app()->runningUnitTests()) {
            Schema::dropIfExists('wait_list_messages');
        }
    }
};
