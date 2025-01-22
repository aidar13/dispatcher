<?php

declare(strict_types=1);

namespace App\Module\Settings\Models;

use Database\Factories\SettingsFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Settings.
 *
 * @property int $id
 * @property string $key
 * @property string $value
 * @property string $label
 * @property string $type
 *
 * @package namespace App\Module\Settings\Models;
 */
final class Settings extends Model
{
    use HasFactory;

    protected $table = 'settings';

    public const ID_SMS              = 1;
    public const ID_PUSH             = 2;
    public const ID_EMAIL            = 3;
    public const ID_CRM_MINDSALE     = 4;
    public const ID_YANDEX_ROUTING   = 5;
    public const ID_YANDEX_SECTOR    = 6;
    public const ID_TELEGRAM_MESSAGE = 7;

    public const SMS              = 'sms';
    public const PUSH             = 'push';
    public const EMAIL            = 'email';
    public const CRM_MINDSALE     = 'crm_mindsale';
    public const YANDEX_ROUTING   = 'yandex_routing';
    public const YANDEX_SECTOR    = 'yandex_sector';
    public const TELEGRAM_MESSAGE = 'telegram_message';

    public const TYPE_BOOL = 'bool';

    protected $fillable = [
        'key',
        'value',
        'label',
        'type'
    ];

    protected static function newFactory(): SettingsFactory
    {
        return SettingsFactory::new();
    }

    public function isEnabled(): bool
    {
        if ($this->type === self::TYPE_BOOL) {
            return (bool)$this->value;
        }

        return false;
    }
}
