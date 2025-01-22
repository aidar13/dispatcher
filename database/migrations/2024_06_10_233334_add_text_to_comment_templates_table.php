<?php

use App\Module\Status\Models\CommentTemplate;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        DB::table('comment_templates')->insert($this->data());
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        foreach ($this->data() as $record) {
            DB::table('comment_templates')->where($record)->delete();
        }
    }

    private function data(): array
    {
        return [
            [
                'text'    => 'Забор вовремя, поздняя приёмка',
                'type_id' => CommentTemplate::ORDER_TAKE_TYPE_ID
            ],
            [
                'text'    => 'Груз не готов, нет переноса ОРК/КЦ',
                'type_id' => CommentTemplate::ORDER_TAKE_TYPE_ID
            ],
            [
                'text'    => 'Вовремя, возврат - нет системного статуса отгрузки на склад',
                'type_id' => CommentTemplate::ORDER_TAKE_TYPE_ID
            ],
            [
                'text'    => 'Вовремя, время забора вне периода работы',
                'type_id' => CommentTemplate::ORDER_TAKE_TYPE_ID
            ],
            [
                'text'    => 'Отправитель на звонки не отвечает, нет переноса ОРК/КШ',
                'type_id' => CommentTemplate::ORDER_TAKE_TYPE_ID
            ],
            [
                'text'    => 'Груз не поместился, некорректные данные в заказе',
                'type_id' => CommentTemplate::ORDER_TAKE_TYPE_ID
            ],
            [
                'text'    => 'Отмена заказа',
                'type_id' => CommentTemplate::ORDER_TAKE_TYPE_ID
            ],
            [
                'text'    => 'Ошибка ИС, не отображался в BPMS/ПК',
                'type_id' => CommentTemplate::ORDER_TAKE_TYPE_ID
            ],
            [
                'text'    => 'Вовремя, нет приёмки терминалом',
                'type_id' => CommentTemplate::ORDER_TAKE_TYPE_ID
            ],
            [
                'text'    => 'Вовремя, не закрыт в МПК',
                'type_id' => CommentTemplate::ORDER_TAKE_TYPE_ID
            ],
            [
                'text'    => 'Груз не готов, нет переноса ОРК/КИ',
                'type_id' => CommentTemplate::ORDER_TAKE_TYPE_ID
            ],
            [
                'text'    => 'Не успел (задержка на предыдущем адресе)',
                'type_id' => CommentTemplate::ORDER_TAKE_TYPE_ID
            ],
            [
                'text'    => 'Не успел (по времени работы клиента)',
                'type_id' => CommentTemplate::ORDER_TAKE_TYPE_ID
            ],
            [
                'text'    => 'Не успел (поломка автомашины)',
                'type_id' => CommentTemplate::ORDER_TAKE_TYPE_ID
            ],
            [
                'text'    => 'Некорректный адрес',
                'type_id' => CommentTemplate::DELIVERY_TYPE_ID
            ],
            [
                'text'    => 'Отказ от получения (повреждение/несоответствие груза)',
                'type_id' => CommentTemplate::DELIVERY_TYPE_ID
            ],
            [
                'text'    => 'На звонки не отвечает',
                'type_id' => CommentTemplate::DELIVERY_TYPE_ID
            ],
            [
                'text'    => 'Не успел (задержка на предыдущем адресе)',
                'type_id' => CommentTemplate::DELIVERY_TYPE_ID
            ],
            [
                'text'    => 'Не успел (по времени работы клиента)',
                'type_id' => CommentTemplate::DELIVERY_TYPE_ID
            ],
            [
                'text'    => 'Не успел (поломка автомашины)',
                'type_id' => CommentTemplate::DELIVERY_TYPE_ID
            ],
            [
                'text'    => 'Груз не прибыл в регион',
                'type_id' => CommentTemplate::DELIVERY_TYPE_ID
            ],
            [
                'text'    => 'Отмена заказа',
                'type_id' => CommentTemplate::DELIVERY_TYPE_ID
            ],
            [
                'text'    => 'Получатель не работает',
                'type_id' => CommentTemplate::DELIVERY_TYPE_ID
            ],
            [
                'text'    => 'Отложена по просьбе получателя',
                'type_id' => CommentTemplate::DELIVERY_TYPE_ID
            ],
            [
                'text'    => 'Доставлено своевременно (вечерка)',
                'type_id' => CommentTemplate::DELIVERY_TYPE_ID
            ],
            [
                'text'    => 'Отмена груза',
                'type_id' => CommentTemplate::DELIVERY_TYPE_ID
            ],
            [
                'text'    => 'Доставлено своевременно позднее закрытие ДК',
                'type_id' => CommentTemplate::DELIVERY_TYPE_ID
            ],
            [
                'text'    => 'Доставка по графику клиента',
                'type_id' => CommentTemplate::DELIVERY_TYPE_ID
            ],
            [
                'text'    => 'Некорректная дата прибытия ТТН',
                'type_id' => CommentTemplate::DELIVERY_TYPE_ID
            ],
            [
                'text'    => 'Перенос даты доставки',
                'type_id' => CommentTemplate::DELIVERY_TYPE_ID
            ]
        ];
    }
};
