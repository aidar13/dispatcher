<?php

use App\Module\Status\Models\StatusType;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::table('status_types')->insert($this->data());
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        foreach ($this->data() as $record) {
            DB::table('status_types')->where($record)->delete();
        }
    }

    private function data(): array
    {
        return [
            [
                'id'          => StatusType::ID_TAKE_CANCELED,
                'title'       => 'Забор отменен',
                'description' => 'Статус означет что забор отменен',
                'type'        => StatusType::TYPE_TAKE,
                'created_at'  => Carbon::now(),
                'updated_at'  => Carbon::now()
            ],
            [
                'id'          => StatusType::ID_PICKUP,
                'title'       => 'Самовывоз',
                'description' => 'Статус означет что доставку заберет клиент со склада',
                'type'        => StatusType::TYPE_DELIVERY,
                'created_at'  => Carbon::now(),
                'updated_at'  => Carbon::now()
            ],
            [
                'id'          => StatusType::ID_RECEIVER_CANCELED,
                'title'       => 'Отменен получателем',
                'description' => 'Статус означет что доставка отменена получателем',
                'type'        => StatusType::TYPE_DELIVERY,
                'created_at'  => Carbon::now(),
                'updated_at'  => Carbon::now()
            ],
            [
                'id'          => StatusType::ID_DATE_CHANGE,
                'title'       => 'Изменена дата доставки',
                'description' => 'Статус означет что изменили дату доставки',
                'type'        => StatusType::TYPE_DELIVERY,
                'created_at'  => Carbon::now(),
                'updated_at'  => Carbon::now()
            ],
            [
                'id'          => StatusType::ID_CARGO_RETURNED,
                'title'       => 'Возврат выдачи курьером на склад',
                'description' => 'Статус означет что доставку вернули на склад',
                'type'        => StatusType::TYPE_DELIVERY,
                'created_at'  => Carbon::now(),
                'updated_at'  => Carbon::now()
            ],
            [
                'id'          => StatusType::ID_RECEIVER_MISSING,
                'title'       => 'Получатель недоступен',
                'description' => 'Статус означет что получатель не выходит на связь',
                'type'        => StatusType::TYPE_DELIVERY,
                'created_at'  => Carbon::now(),
                'updated_at'  => Carbon::now()
            ]
        ];
    }
};
