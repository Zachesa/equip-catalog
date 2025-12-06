<?php
namespace App\Enums;

enum EquipmentStatus: string
{
    case ACTIVE = 'active';
    case DISCONTINUED = 'discontinued';
    case ARCHIVED = 'archived';

    public function label(): string
    {
        return match ($this) {
            self::ACTIVE => 'В наличии',
            self::DISCONTINUED => 'Снято с производства',
            self::ARCHIVED => 'В архиве',
        };
    }

    public function isVisible(): bool
    {
        return $this === self::ACTIVE;
    }

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}