<?php

use App\Module\Order\Models\AdditionalServiceType;
use App\Module\Order\Models\AdditionalServiceValue;
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
        Schema::create('additional_service_values', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('status_id')->nullable();
            $table->unsignedBigInteger('type_id')->nullable();
            $table->morphs('client');
            $table->string('value')->nullable();
            $table->integer('duration')->nullable();
            $table->string('cost_total')->nullable();
            $table->string('cost_per_hour')->nullable();
            $table->string('paid_price_per_hour')->nullable();
            $table->string('paid_price_total')->nullable();
            $table->unsignedBigInteger('carrier_id')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });

        Schema::create('additional_service_types', function (Blueprint $table) {
            $table->id();
            $table->string('code')->nullable();
            $table->string('name')->nullable();
            $table->tinyInteger('active')->default(1);
            $table->boolean('is_billingable')->nullable()->default(false);
            $table->softDeletes();
            $table->timestamps();
        });

        $this->insertData();
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::dropIfExists('additional_service_types');
        Schema::dropIfExists('additional_service_values');
    }

    private function insertData(): void
    {
        DB::table('additional_service_types')->insert([
            'id'             => AdditionalServiceType::ID_DELIVERY,
            'code'           => 'delivery',
            'name'           => 'Доставка до поселка',
            'is_billingable' => 0
        ]);
        DB::table('additional_service_types')->insert([
            'id'             => AdditionalServiceType::ID_LOADER,
            'code'           => 'loader',
            'name'           => 'Грузчики',
            'is_billingable' => 1
        ]);
        DB::table('additional_service_types')->insert([
            'id'             => AdditionalServiceType::ID_WOOD_BOX,
            'code'           => 'wood_box',
            'name'           => 'Деревянный короб',
            'is_billingable' => 0
        ]);
        DB::table('additional_service_types')->insert([
            'id'             => AdditionalServiceType::ID_SOFT_PACKAGE,
            'code'           => 'soft_package',
            'name'           => 'Мягкая упаковка',
            'is_billingable' => 1
        ]);
        DB::table('additional_service_types')->insert([
            'id'             => AdditionalServiceType::ID_PALLET,
            'code'           => 'pallet',
            'name'           => 'Паллета',
            'is_billingable' => 1
        ]);
        DB::table('additional_service_types')->insert([
            'id'             => AdditionalServiceType::ID_GRID,
            'code'           => 'grid',
            'name'           => 'Обрешетка',
            'is_billingable' => 1
        ]);
        DB::table('additional_service_types')->insert([
            'id'             => AdditionalServiceType::ID_MANIPULATOR,
            'code'           => 'manipulator',
            'name'           => 'Манипулятор',
            'is_billingable' => 1
        ]);
        DB::table('additional_service_types')->insert([
            'id'             => AdditionalServiceType::ID_CRANE,
            'code'           => 'crane',
            'name'           => 'Кран',
            'is_billingable' => 1
        ]);
        DB::table('additional_service_types')->insert([
            'id'             => AdditionalServiceType::ID_CAR,
            'code'           => 'car',
            'name'           => 'Авто',
            'is_billingable' => 0
        ]);
        DB::table('additional_service_types')->insert([
            'id'             => AdditionalServiceType::ID_HYDRAULIC_TROLLEY,
            'code'           => 'hydraulic_trolley',
            'name'           => 'Рохля',
            'is_billingable' => 1
        ]);
        DB::table('additional_service_types')->insert([
            'id'             => AdditionalServiceType::ID_RISE_TO_THE_FLOOR,
            'code'           => 'rise_to_the_floor',
            'name'           => 'Подъем на этаж',
            'is_billingable' => 1
        ]);
    }
};
