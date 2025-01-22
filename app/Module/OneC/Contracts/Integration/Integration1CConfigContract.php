<?php

declare(strict_types=1);

namespace App\Module\OneC\Contracts\Integration;

use App\Module\OneC\DTO\Integration\Integration1CConfigDTO;

interface Integration1CConfigContract
{
    public function getMobileAppConfig(): Integration1CConfigDTO;
    public function getMainConfig(): Integration1CConfigDTO;
    public function getMain1CBuhConfig(): Integration1CConfigDTO;
}
