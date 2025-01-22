<?php

namespace App\Traits;

trait ToArrayTrait
{
    public function toArray(): array
    {
        $array = (array) $this;
        return array_filter($array, function ($value) {
            return ($value !== null && $value !== false && $value !== '');
        });
    }
}
