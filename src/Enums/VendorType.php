<?php
// src/Enums/VendorType.php

namespace App\Enums;

enum VendorType: string
{
    case MANUFACTURER = 'manufacturer';
    case DISTRIBUTOR = 'distributor';
    case SERVICE_CENTER = 'service_center';

    public function label(): string
    {
        return match ($this) {
            self::MANUFACTURER => 'Производитель',
            self::DISTRIBUTOR => 'Дистрибьютор',
            self::SERVICE_CENTER => 'Сервисный центр',
        };
    }
}