<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::create('car_types', function (Blueprint $table) {
            $table->id();
            $table->string('title', 256);
            $table->float('volume');
            $table->integer('capacity');
            $table->softDeletes();
            $table->timestamps();
        });

        DB::table('car_types')->insert([
            [
                'id'       => 1,
                'title'    => 'Легковые',
                'volume'   => 3,
                'capacity' => 500,
            ],
            [
                'id'       => 2,
                'title'    => 'Минивен',
                'volume'   => 5,
                'capacity' => 700,
            ],
            [
                'id'       => 3,
                'title'    => 'Газель',
                'volume'   => 2000,
                'capacity' => 0,
            ],
            [
                'id'       => 4,
                'title'    => '5 тонник',
                'volume'   => 0,
                'capacity' => 5,
            ],
            [
                'id'       => 5,
                'title'    => '10 тонник',
                'volume'   => 0,
                'capacity' => 0,
            ],
            [
                'id'       => 6,
                'title'    => 'Фура',
                'volume'   => 0,
                'capacity' => 0,
            ],
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::dropIfExists('car_types');
    }
};
