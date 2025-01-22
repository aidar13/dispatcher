<?php

use App\Module\Settings\Models\Settings;
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
    public function up(): void
    {
        DB::table('settings')->insert([
            'id'    => Settings::ID_SMS,
            'key'   => Settings::SMS,
            'value' => '0',
            'type'  => Settings::TYPE_BOOL,
            'label' => 'Включить/Выключить отправку смс получателям груза'
        ]);

        DB::table('settings')->insert([
            'id'    => Settings::ID_PUSH,
            'key'   => Settings::PUSH,
            'value' => '0',
            'type'  => Settings::TYPE_BOOL,
            'label' => 'Включить/Выключить отправку push получателям груза'
        ]);

        DB::table('settings')->insert([
            'id'    => Settings::ID_EMAIL,
            'key'   => Settings::EMAIL,
            'value' => '0',
            'type'  => Settings::TYPE_BOOL,
            'label' => 'Включить/Выключить отправку писем на почту'
        ]);

        DB::table('settings')->insert([
            'id'    => Settings::ID_CRM_MINDSALE,
            'key'   => Settings::CRM_MINDSALE,
            'value' => '0',
            'type'  => Settings::TYPE_BOOL,
            'label' => 'Вклчюить/Выключить отправку в crm mindsales'
        ]);

        DB::table('settings')->insert([
            'id'    => Settings::ID_YANDEX_ROUTING,
            'key'   => Settings::YANDEX_ROUTING,
            'value' => '0',
            'type'  => Settings::TYPE_BOOL,
            'label' => 'Вклчюить/Выключить интеграцию с яндекс маршрутизацией'
        ]);

        DB::table('settings')->insert([
            'id'    => Settings::ID_YANDEX_SECTOR,
            'key'   => Settings::YANDEX_SECTOR,
            'value' => '0',
            'type'  => Settings::TYPE_BOOL,
            'label' => 'Вклчюить/Выключить интеграцию с яндекс маршрутизацией отправку секторов'
        ]);

        DB::table('settings')->insert([
            'id'    => Settings::ID_TELEGRAM_MESSAGE,
            'key'   => Settings::TELEGRAM_MESSAGE,
            'value' => '0',
            'type'  => Settings::TYPE_BOOL,
            'label' => 'Вклчюить/Выключить отправку сообщении в telegram'
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        DB::table('settings')->delete(Settings::ID_SMS);
        DB::table('settings')->delete(Settings::ID_PUSH);
        DB::table('settings')->delete(Settings::ID_EMAIL);
        DB::table('settings')->delete(Settings::ID_CRM_MINDSALE);
        DB::table('settings')->delete(Settings::ID_YANDEX_ROUTING);
        DB::table('settings')->delete(Settings::ID_YANDEX_SECTOR);
        DB::table('settings')->delete(Settings::ID_TELEGRAM_MESSAGE);
    }
};
