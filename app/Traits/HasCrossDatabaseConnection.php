<?php

namespace App\Traits;

trait HasCrossDatabaseConnection
{
    public function setTableName(string $connection, string $table): string
    {
        if (app()->runningUnitTests()) {
            return $table;
        }
        $database = config("database.connections.$connection.database");

        return implode('.', [$database, $table]);
    }
}
