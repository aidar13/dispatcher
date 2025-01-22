<?php

declare(strict_types=1);

namespace App\ValueObjects;

use Illuminate\Support\Facades\Log;

final class PhoneNumber
{
    private string $phone;

    const COUNTRY = ["KZ"];

    public function __construct(string $phone)
    {
        $this->phone = $this->isPhone($phone) ? phone($phone, PhoneNumber::COUNTRY)->formatE164() : $phone;
    }

    public static function isPhone(string $phone): bool
    {
        try {
            return phone($phone, PhoneNumber::COUNTRY)->isOfCountry(PhoneNumber::COUNTRY);
        } catch (\Exception $exception) {
            Log::info("Не правильный номер телефона {$phone} {$exception->getMessage()}");
        }

        return false;
    }

    public function getPhone(): string
    {
        return $this->phone;
    }

    public function __toString()
    {
        return $this->getPhone();
    }
}
