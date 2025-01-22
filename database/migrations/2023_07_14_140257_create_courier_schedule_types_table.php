<?php

use App\Module\Courier\Models\CourierScheduleType;
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
        Schema::create('courier_schedule_types', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->time('work_time_from');
            $table->time('work_time_until');
            $table->timestamps();
        });

        DB::table('courier_schedule_types')
            ->insert($this->getData());
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::dropIfExists('courier_schedule_types');
    }

    private function getData(): array
    {
        return [
            [
                'id' => CourierScheduleType::ID_FIRST_WAVE,
                'title'           => 'До обеда',
                'work_time_from'  => '08:00',
                'work_time_until' => '14:00'
            ],
            [
                'id' => CourierScheduleType::ID_SECOND_WAVE,
                'title'           => 'После обеда',
                'work_time_from'  => '14:00',
                'work_time_until' => '22:00'
            ],
            [
                'id' => CourierScheduleType::ID_THIRD_WAVE,
                'title'           => 'Полный день',
                'work_time_from'  => '08:00',
                'work_time_until' => '22:00'
            ],
        ];
    }
};
