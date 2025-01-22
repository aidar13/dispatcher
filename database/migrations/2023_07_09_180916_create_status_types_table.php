<?php

use App\Module\Status\Models\StatusType;
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
        Schema::create('status_types', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('description');
            $table->string('type');
            $table->timestamps();
            $table->softDeletes();
        });

        DB::table('status_types')->insert($this->getData());
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::dropIfExists('status_types');
    }

    private function getData(): array
    {
        return [
            [
                'id'          => StatusType::ID_NOT_ASSIGNED,
                'title'       => 'Не назначен на курьера',
                'description' => 'Статус по умолчанию, появляется когда заказ создан в 1С или отвязывают курьера от забора',
                'type'        => StatusType::TYPE_TAKE
            ],
            [
                'id'          => StatusType::ID_ASSIGNED,
                'title'       => 'Назначен на курьера',
                'description' => 'Статус означает что курьера назначили на забор',
                'type'        => StatusType::TYPE_TAKE
            ],
            [
                'id'          => StatusType::ID_TAKEN,
                'title'       => 'Забран курьером',
                'description' => 'Статус означает что курьер забрал груз у отправителя',
                'type'        => StatusType::TYPE_TAKE
            ],
            [
                'id'          => StatusType::ID_CARGO_HANDLING,
                'title'       => 'Забор отгружен на склад',
                'description' => 'Статус означает что курьер отгрузил забор на склад',
                'type'        => StatusType::TYPE_TAKE
            ],
            [
                'id'          => StatusType::ID_DELIVERY_CREATED,
                'title'       => 'Создан доставка',
                'description' => 'Статус означает что груз готов к выдаче курьеру на доставку',
                'type'        => StatusType::TYPE_DELIVERY
            ],
            [
                'id'          => StatusType::ID_IN_DELIVERING,
                'title'       => 'Выдан на доставку',
                'description' => 'Статус означает что груз дали курьеру на доставку',
                'type'        => StatusType::TYPE_DELIVERY
            ],
            [
                'id'          => StatusType::ID_DELIVERED,
                'title'       => 'Груз доставлен',
                'description' => 'Статус означает что груз доставлен получателю',
                'type'        => StatusType::TYPE_DELIVERY
            ],
        ];
    }
};
