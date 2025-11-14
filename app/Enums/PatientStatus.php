<?php

namespace App\Enums;

enum PatientStatus: string
{
    case Active = 'active';
    case Inactive = 'inactive';
    case Deceased = 'deceased';

    public function label(): string
    {
        return match ($this) {
            self::Active => 'Aktif',
            self::Inactive => 'Tidak Aktif',
            self::Deceased => 'Meninggal',
        };
    }

    public function color(): string
    {
        return match ($this) {
            self::Active => 'green',
            self::Inactive => 'amber',
            self::Deceased => 'zinc',
        };
    }
}
