<?php

declare(strict_types=1);

namespace App\Module\Gateway\Model;

use Database\Factories\TokenFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

/**
 * App\Module\Gateway\Model\Token
 *
 * @property mixed|string $refresh_token
 * @property mixed|string $access_token
 * @property mixed|string $expires_in
 * @property mixed|string $scope
 * @property mixed|string $adapter
 * @property mixed $id
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @method static TokenFactory factory($count = null, $state = [])
 */
final class Token extends Model
{
    use HasFactory;

    protected $table = "oauth_tokens";

    protected static function newFactory()
    {
        return TokenFactory::new();
    }
}
