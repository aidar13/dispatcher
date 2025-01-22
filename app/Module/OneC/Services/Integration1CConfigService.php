<?php

declare(strict_types=1);

namespace App\Module\OneC\Services;

use App\Module\OneC\Contracts\Integration\Integration1CConfigContract;
use App\Module\OneC\DTO\Integration\Integration1CConfigDTO;

final class Integration1CConfigService implements Integration1CConfigContract
{
    /**
     * Конфиг для запросов в 1С на все запросы которые начинаются
     * с /Integrationmac/...
     *
     * @return Integration1CConfigDTO
     */
    public function getMobileAppConfig(): Integration1CConfigDTO
    {
        $uri = config('urls.1C.uri');
        $token = config('urls.1C.token.mobile_app');

        list($login, $password) = $this->getCredentials();

        return new Integration1CConfigDTO($uri, $login, $password, $token);
    }

    /**
     * Конфиг для запросов в 1С на все запросы которые начинаются
     * с /integrationinternal/...
     *
     * @return Integration1CConfigDTO
     */
    public function getMainConfig(): Integration1CConfigDTO
    {
        $uri = config('urls.1C.uri');
        $token = config('urls.1C.token.main');

        list($login, $password) = $this->getCredentials();

        return new Integration1CConfigDTO($uri, $login, $password, $token);
    }

    /**
     * @return array
     */
    private function getCredentials(): array
    {
        $login    = config('urls.1C.basic_auth.login');
        $password = config('urls.1C.basic_auth.password');
        return array($login, $password);
    }

    /**
     * @return Integration1CConfigDTO
     */
    public function getMain1CBuhConfig(): Integration1CConfigDTO
    {
        $uri = config('urls.1C.buh_uri');
        $token = config('urls.1C.token.buh_main');

        list($login, $password) = $this->getCredentials();

        return new Integration1CConfigDTO($uri, $login, $password, $token);
    }
}
