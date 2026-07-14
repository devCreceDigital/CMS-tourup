<?php

namespace App\Enums;

enum ProgramStatus: string
{
    case Active = 'active';
    case Inactive = 'inactive';

    public function label(): string
    {
        return match ($this) {
            self::Active => 'Activo',
            self::Inactive => 'Inactivo',
        };
    }

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}
