<?php

namespace App\Enums;

enum TripStatus: string
{
    case Active = 'active';
    case OnSale = 'on_sale';
    case Completed = 'completed';
    case Inactive = 'inactive';
    case Cancelled = 'cancelled';

    public function label(): string
    {
        return match ($this) {
            self::Active => 'Activo',
            self::OnSale => 'En Venta',
            self::Completed => 'Completado',
            self::Inactive => 'Inactivo',
            self::Cancelled => 'Cancelado',
        };
    }

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}
