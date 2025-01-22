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
            Schema::create('ref_statuses', function (Blueprint $table) {
                $table->id();
                $table->string('name')->comment('Название');
                $table->integer('code')->unique()->comment('Код статуса');
                $table->integer('order')->comment('Порядковый номер');
                $table->string('comment')->nullable()->comment('Комментарий');
                $table->boolean('is_visible')->default('1')->comment('Видимость');
                $table->boolean('is_active')->default('1')->comment('Активность');
                $table->integer('wait_list_type')->nullable()->comment('Тип листа ожидания');
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
            Schema::dropIfExists('ref_statuses');
        }
    }
};
