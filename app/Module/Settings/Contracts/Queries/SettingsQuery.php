<?php

declare(strict_types=1);

namespace App\Module\Settings\Contracts\Queries;

use App\Module\Settings\Models\Settings;

interface SettingsQuery
{
    public function findByKey(string $key): Settings;
}
